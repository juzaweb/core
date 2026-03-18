<?php

namespace Juzaweb\Modules\Core\Modules\Support;

class Stub
{
    /**
     * The stub path.
     */
    protected string $path;

    /**
     * The base path of stub file.
     */
    protected static ?string $basePath = null;

    /**
     * The replacements array.
     */
    protected array $replaces = [];

    /**
     * The contructor.
     */
    public function __construct(string $path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * Create new self instance.
     *
     *
     * @return self
     */
    public static function create(string $path, array $replaces = [])
    {
        return new static($path, $replaces);
    }

    /**
     * Set stub path.
     *
     *
     * @return self
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath()
    {
        $path = static::getBasePath().ltrim($this->path, '/');

        return file_exists($path) ? $path : dirname(__DIR__, 2).'/stubs/'.ltrim($this->path, '/');
    }

    /**
     * Set base path.
     */
    public static function setBasePath(string $path)
    {
        static::$basePath = $path;
    }

    /**
     * Get base path.
     *
     * @return string|null
     */
    public static function getBasePath()
    {
        return static::$basePath;
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$'.strtoupper($search).'$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render()
    {
        return $this->getContents();
    }

    /**
     * Save stub to specific path.
     *
     *
     * @return bool
     */
    public function saveTo(string $path, string $filename)
    {
        return file_put_contents($path.'/'.$filename, $this->getContents());
    }

    /**
     * Set replacements array.
     *
     *
     * @return $this
     */
    public function replace(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces()
    {
        return $this->replaces;
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
