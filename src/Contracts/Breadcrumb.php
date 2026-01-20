<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

interface Breadcrumb
{
    public function add(string $title, string $url = null): void;

    public function items(array $items): void;

    public function addItems(array $items): void;

    public function getItems(): array;

    public function toArray(): array;
}
