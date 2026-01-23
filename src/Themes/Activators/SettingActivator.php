<?php

namespace Juzaweb\Modules\Core\Themes\Activators;

use Illuminate\Contracts\Container\Container;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Themes\Contracts\ThemeActivatorInterface;
use Juzaweb\Modules\Core\Themes\Theme;

class SettingActivator implements ThemeActivatorInterface
{
    /**
     * Setting instance
     *
     * @var Setting
     */
    private Setting $setting;

    /**
     * The settings key for active theme
     *
     * @var string
     */
    private string $settingKey;

    public function __construct(Container $app)
    {
        $this->setting = $app[Setting::class];
        $this->settingKey = $app['config']->get('themes.activators.setting.key', 'theme');
    }

    /**
     * @inheritDoc
     */
    public function activate(Theme $theme): void
    {
        $this->setActiveByName($theme->name());
    }

    /**
     * @inheritDoc
     */
    public function isActive(Theme $theme): bool
    {
        return $this->getActiveName() === $theme->name();
    }

    /**
     * @inheritDoc
     */
    public function setActive(Theme $theme): void
    {
        $this->setActiveByName($theme->name());
    }

    /**
     * @inheritDoc
     */
    public function setActiveByName(string $name): void
    {
        $this->setting->set($this->settingKey, $name);
    }

    /**
     * @inheritDoc
     */
    public function getActiveName(): ?string
    {
        return $this->setting->get($this->settingKey);
    }

    /**
     * @inheritDoc
     */
    public function delete(Theme $theme): void
    {
        if ($this->isActive($theme)) {
            $this->setting->set($this->settingKey, null);
        }
    }

    /**
     * @inheritDoc
     */
    public function reset(): void
    {
        $this->setting->set($this->settingKey, null);
    }
}
