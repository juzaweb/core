<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Contracts;

interface Breadcrumb
{
    public function add(string $title, string $url = null): void;

    public function items(array $items): void;

    public function addItems(array $items): void;

    public function getItems(): array;
}
