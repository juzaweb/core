<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support\Traits;

trait HasRules
{
    protected array $rules = [];

    /**
     * Toggle the required rule on the model validation rules.
     *
     * @param bool $required Whether the field is required or not. Default is true.
     * @return static
     */
    public function required(bool $required = true): static
    {
        if ($required) {
            $this->rules = array_merge($this->rules, ['required']);
        } else {
            $this->rules = array_diff($this->rules, ['required']);
        }

        return $this;
    }

    /**
     * Set the field as nullable or not nullable.
     *
     * @param bool $nullable Whether the field is nullable or not
     * @return static
     */
    public function nullable(bool $nullable = true): static
    {
        if ($nullable) {
            $this->rules = array_merge($this->rules, ['nullable']);
        } else {
            $this->rules = array_diff($this->rules, ['nullable']);
        }

        return $this;
    }

    /**
     * Set the rules for the Laravel application.
     *
     * @param array $rules The validation rules for the application
     * @return static
     */
    public function rules(array $rules): static
    {
        $this->rules = $rules;

        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
