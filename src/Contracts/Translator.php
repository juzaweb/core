<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Contracts;

interface Translator
{
    public function translate(string $text, string $source, string $target, bool $isHTML = false): ?string;

    public function withProxy(string|array $proxy): static;
}
