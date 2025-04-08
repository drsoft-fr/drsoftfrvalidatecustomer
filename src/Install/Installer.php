<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Install;

use DrSoftFr\Module\ValidateCustomer\Data\Configuration\SettingConfiguration;
use Exception;
use Module;
use Throwable;

/**
 * Class responsible for modifications needed during installation/uninstallation of the module.
 */
final class Installer
{
    const HOOKS = [
        'actionAuthentication',
        'actionCustomerAccountAdd',
        'actionFrontControllerSetVariables',
        'actionListMailThemes',
        'actionObjectUpdateAfter',
    ];

    /**
     * @var FixturesInstaller
     */
    private $fixturesInstaller;

    /**
     * @var SettingConfiguration
     */
    private $settingConfiguration;

    public function __construct(
        FixturesInstaller    $fixturesInstaller,
        SettingConfiguration $settingConfiguration
    )
    {
        $this->fixturesInstaller = $fixturesInstaller;
        $this->settingConfiguration = $settingConfiguration;
    }

    /**
     * Module's installation entry point.
     *
     * @param Module $module The module to install.
     *
     * @return bool True if the module is successfully installed.
     *
     * @throws Exception If an error occurs during the installation process.
     */
    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            throw new Exception('An error occurred when registering hooks for the module.');
        }

        $fixturesConfiguration = $this->fixturesInstaller->install();
        $configuration = array_merge($this->settingConfiguration::CONFIGURATION_DEFAULT_VALUES, $fixturesConfiguration);

        $this->settingConfiguration->updateConfiguration($configuration);

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @param Module $module The module to uninstall.
     *
     * @return bool True if the module is successfully uninstalled
     *
     * @throws Exception if an error occurs when deleting the module parameters.
     */
    public function uninstall(Module $module): bool
    {
        try {
            $this->settingConfiguration->removeConfiguration();
        } catch (Throwable $t) {
            throw new Exception('An error occurred when deleting the module parameters.');
        }

        return true;
    }

    /**
     * Register hooks for the module.
     *
     * @param Module $module
     *
     * @return bool
     */
    private function registerHooks(Module $module): bool
    {
        return (bool)$module->registerHook(self::HOOKS);
    }

    /**
     * @return FixturesInstaller
     */
    public function getFixturesInstaller(): FixturesInstaller
    {
        return $this->fixturesInstaller;
    }

    /**
     * @return SettingConfiguration
     */
    public function getSettingConfiguration(): SettingConfiguration
    {
        return $this->settingConfiguration;
    }
}
