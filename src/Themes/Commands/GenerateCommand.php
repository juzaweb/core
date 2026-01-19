<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Juzaweb\Modules\Core\Themes\Theme;

abstract class GenerateCommand extends Command
{
    protected function getStyleProviderContents(Theme $theme): false|string
    {
        $providerFile = $theme->path('Providers/StyleServiceProvider.php');
        if (!file_exists($providerFile)) {
            $content = $this->generateContents(
                'provider.stub',
                [
                    'NAMESPACE' => 'Juzaweb\\Themes\\' . Str::studly($theme->name()) . '\\Providers',
                    'CLASS' => 'StyleServiceProvider',
                ]
            );
        } else {
            $content = file_get_contents($providerFile);
        }

        return $content;
    }

    protected function addToProviderBoot(string $code, string $content): string
    {
        $pattern = '/(public function boot\s*\(\)\s*\{)([\s\S]*?)(^\s*\})/m';

        $replacement = '$1$2' . $code . '$3';

        return preg_replace($pattern, $replacement, $content);
    }

    protected function addUseClass(string $content, string $className): string
    {
        $useStatement = "use {$className};";

        if (!str_contains($content, $useStatement)) {
            // Tìm vị trí cuối cùng của nhóm use hiện tại
            if (preg_match_all('/^use\s+[^;]+;/m', $content, $allMatches, PREG_OFFSET_CAPTURE)) {
                // $allMatches[0] is an array of matches; pick the last one
                $lastMatch = end($allMatches[0]);
                // $lastMatch is [matchedString, offset]
                $matchedString = $lastMatch[0];
                $matchedOffset = $lastMatch[1];

                $insertPos = $matchedOffset + strlen($matchedString);
                $content = substr_replace($content, "\n{$useStatement}", $insertPos, 0);
            } else {
                // If there is no use block, add after namespace
                $content = preg_replace(
                    '/(namespace\s+[^\n;]+;)/',
                    "$1\n\n{$useStatement}",
                    $content
                );
            }
        }

        return $content;
    }

    protected function writeStyleProvider(Theme $theme, string $newContent): void
    {
        file_put_contents($theme->path('Providers/StyleServiceProvider.php'), $newContent);
    }

    protected function generateContents(string $stub, array $data): string
    {
        return (new Stub($stub, $data))->render();
    }
}
