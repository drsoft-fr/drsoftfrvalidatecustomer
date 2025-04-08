<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Data\Factory;

use DrSoftFr\Module\ValidateCustomer\Data\Configuration\SettingConfiguration;

/**
 * Class SettingDataFactory is in charge of accessing the ValidateCustomer settings in PrestaShop configuration
 */
final class SettingDataFactory
{
    /**
     * @var SettingConfiguration
     */
    private $configuration;

    /**
     * @param SettingConfiguration $configuration
     */
    public function __construct(
        SettingConfiguration $configuration
    )
    {
        $this->configuration = $configuration;
    }

    /**
     * @return array the form data as an associative array
     */
    public function getData(): array
    {
        return $this->configuration->getConfiguration();
    }
}
