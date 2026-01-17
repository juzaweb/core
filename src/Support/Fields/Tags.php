<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Fields;

use Illuminate\Database\Eloquent\Model;

class Tags extends Select
{
    public function __construct(
        protected string|Model $label,
        protected string $name,
        protected array $options = []
    ) {
        parent::__construct($label, $name, $options);
        $this->options['multiple'] = true;
        $this->options['classes'] = ['tags-input'];
    }
}
