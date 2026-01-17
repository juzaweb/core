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
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable;
use Juzaweb\Modules\Core\Translations\Models\Language;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelTranslateCommand extends Command
{
    protected $name = 'translation:model';

    public function handle(): int
    {
        $websiteId = $this->option('website-id');
        $model = $this->argument('model');
        $limit = $this->option('limit');

        if (!class_exists($model)) {
            $this->error("Model {$model} does not exist.");
            return self::FAILURE;
        }

        if (!is_subclass_of($model, Translatable::class)) {
            $this->error("Model {$model} does not implement the Translatable interface.");
            return self::FAILURE;
        }

        $source = $this->option('source') ?? config('translatable.fallback_locale');

        if ($target = $this->option('target')) {
            $targets = explode(',', $target);
        } else {
            // Get all languages for this website except the source language
            $targets = Language::where('website_id', $websiteId)
                ->where('code', '!=', $source)
                ->pluck('code')
                ->toArray();
        }

        if (empty($targets)) {
            $this->info("No target languages found for website ID {$websiteId}.");
            return self::SUCCESS;
        }

        $totalTranslations = 0;
        foreach ($targets as $target) {
            /** @var class-string<Translatable|Model> $modelClass */
            $modelClass = $model;

            // Query models for the specific website that haven't been translated to the target language
            $modelClass::with(['translations' => fn ($query) => $query->where('locale', array_merge([$source], [$target]))])
                ->notTranslatedIn($target)
                ->chunkById(
                    100,
                    function ($records) use ($target, $source, $limit, &$totalTranslations) {
                        foreach ($records as $record) {
                            if ($limit && $totalTranslations >= $limit) {
                                $this->info("Translation limit of {$limit} reached. Stopping further translations.");
                                return false; // Stop further processing
                            }

                            model_translate(
                                $record,
                                $source,
                                $target
                            );

                            $totalTranslations++;
                            $this->info("Translating record ID {$record->id} from {$source} to {$target}");
                        }
                    }
                );
        }

        return self::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The model class to translate'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['source', 's', InputOption::VALUE_OPTIONAL, 'The source language to translate from', null],
            ['target', 't', InputOption::VALUE_OPTIONAL, 'The target languages to translate to, separated by commas', null],
            ['limit', null, InputOption::VALUE_OPTIONAL, 'Limit the number of records to translate', null],
        ];
    }
}
