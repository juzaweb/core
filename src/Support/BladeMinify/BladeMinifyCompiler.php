<?php

namespace Juzaweb\Modules\Core\Support\BladeMinify;

use Illuminate\View\Compilers\BladeCompiler as BaseCompiler;

class BladeMinifyCompiler extends BaseCompiler
{
    /**
     * Compile the given Blade template contents.
     *
     * @param  string  $value
     * @return string
     */
    public function compileString($value): string
    {
        $contents = parent::compileString($value);
        return $this->minifyString($contents);
    }

    /**
     * Minify the compiled Blade template contents.
     *
     * @param  string  $value
     * @return string
     */
    protected function minifyString($value): string
    {
        return Blade::minify($value, [
            //'cssMinifier' => [CSSMin::class, 'minify'],
            'jsMinifier' => function ($contents) {
                return JSMin::minify($contents);
            },
        ]);
    }
}
