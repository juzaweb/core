<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class TranslateViewTextCommand extends Command
{
    protected $name = 'translate:views';

    protected $description = 'Wrap plain text in Blade views with the __() translation function';

    public function handle(): void
    {
        $path = base_path($this->argument('path'));

        if (!is_dir($path)) {
            $this->error("Path does not exist: {$path}");
            return;
        }

        $finder = new Finder();
        $finder->files()->in($path)->name('*.blade.php');

        foreach ($finder as $file) {
            $filePath = $file->getRealPath();
            $originalContent = file_get_contents($filePath);

            // Escape blade first
            $escapedContent = $this->escapeBlade($originalContent);

            // Wrap if needed
            $hasWrapper = stripos($escapedContent, '<html') !== false || stripos($escapedContent, '<body') !== false;
            $wrappedHtml = $hasWrapper
                ? $escapedContent
                : '<meta charset="UTF-8"><body>' . $escapedContent . '</body>';

            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;

            $dom->loadHTML(mb_convert_encoding($wrappedHtml, 'HTML-ENTITIES', 'UTF-8'));
            $targetNode = $hasWrapper ? $dom->documentElement : $dom->getElementsByTagName('body')->item(0);

            if (!$targetNode) return;

            $changed = false;
            $this->processNodes($targetNode, $dom, $changed);

            if ($changed) {
                $newContent = $hasWrapper
                    ? $dom->saveHTML()
                    : collect(iterator_to_array($targetNode->childNodes))
                        ->map(fn($n) => $dom->saveHTML($n))->implode('');

                // Restore Blade
                $newContent = $this->restoreBlade($newContent);

                file_put_contents($filePath, $newContent);
                $this->info("Updated: {$filePath}");
            }
        }

        $this->info("Done.");
    }

    protected function escapeBlade($content): array|string|null
    {
        return preg_replace_callback('/(@[a-zA-Z]+\s*\(.*?\)|\{\{.*?\}\})/s', function ($match) {
            return '<!--BLADE:' . base64_encode($match[0]) . '-->';
        }, $content);
    }

    protected function restoreBlade($content): array|string|null
    {
        return preg_replace_callback('/<!--BLADE:(.*?)-->/', function ($match) {
            return base64_decode($match[1]);
        }, $content);
    }

    protected function processNodes(\DOMNode $node, \DOMDocument $dom, &$changed): void
    {
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text = trim($child->textContent);

                // Skip if empty or already a blade directive
                if ($text !== '' && !str_starts_with($text, '{{') && !str_starts_with($text, '@')) {
                    $newText = "{{ __('" . addslashes($text) . "') }}";
                    $newNode = $dom->createTextNode($newText);
                    $node->replaceChild($newNode, $child);
                    $changed = true;
                }
            } elseif ($child->hasChildNodes()) {
                $this->processNodes($child, $dom, $changed);
            }
        }
    }

    protected function getArguments(): array
    {
        return [
            ['path', InputArgument::REQUIRED, 'The path to the Blade views directory'],
        ];
    }
}
