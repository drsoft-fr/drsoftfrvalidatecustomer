<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Data\Configuration;

use DrSoftFr\Module\ValidateCustomer\Config;
use DrSoftFr\Module\ValidateCustomer\Data\Validator\SettingValidator;
use Exception;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use Throwable;

/**
 * Class SettingConfiguration
 *
 * This class handles the configuration ValidateCustomer for the application.
 */
final class SettingConfiguration implements DataConfigurationInterface
{
    const CONFIGURATION_KEYS = [
        'admin_action_customer_account_add_email' => 'DRSOFT_FR_VALIDATE_CUSTOMER_ADMIN_ACTION_CUSTOMER_ACCOUNT_ADD_EMAIL',
        'admin_send_email_on_action_customer_account_add_hook' => 'DRSOFT_FR_VALIDATE_CUSTOMER_ADMIN_EMAIL_ON_ACTION_CUSTOMER_ACCOUNT_ADD',
        'enable_email_approval' => 'DRSOFT_FR_VALIDATE_CUSTOMER_ENABLE_EMAIL_APPROVAL',
        'enable_email_pending_approval' => 'DRSOFT_FR_VALIDATE_CUSTOMER_ENABLE_EMAIL_PENDING_APPROVAL',
    ];

    const CONFIGURATION_DEFAULT_VALUES = [
        'admin_action_customer_account_add_email' => '',
        'admin_send_email_on_action_customer_account_add_hook' => false,
        'enable_email_approval' => false,
        'enable_email_pending_approval' => false,
    ];

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var SettingValidator|null
     */
    private $validator;

    /**
     * @param Configuration $configuration
     * @param SettingValidator|null $validator
     */
    public function __construct(
        Configuration    $configuration,
        SettingValidator $validator = null
    )
    {
        $this->configuration = $configuration;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $configuration = [];

        foreach (self::CONFIGURATION_KEYS as $key => $value) {
            if (in_array(
                $key,
                [
                    'admin_send_email_on_action_customer_account_add_hook',
                    'enable_email_approval',
                    'enable_email_pending_approval',
                ],
                true
            )) {
                $configuration[$key] = $this->configuration->getBoolean($value, self::CONFIGURATION_DEFAULT_VALUES[$key]);

                continue;
            }

            $configuration[$key] = $this->configuration->get($value, self::CONFIGURATION_DEFAULT_VALUES[$key]);
        }

        return $configuration;
    }

    /**
     * Initialize the configuration.
     *
     * This method initializes the configuration by updating the current configuration
     * with the default values defined in `CONFIGURATION_DEFAULT_VALUES` constant.
     * It updates the configuration using the `updateConfiguration` method.
     *
     * @return void
     *
     * @throws Exception
     */
    public function initConfiguration(): void
    {
        $this->updateConfiguration(self::CONFIGURATION_DEFAULT_VALUES);
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $errors = [];

        try {
            $this->validateConfiguration($configuration);

            foreach (self::CONFIGURATION_KEYS as $key => $value) {
                $this->configuration->set($value, $configuration[$key]);
            }
        } catch (Throwable $t) {
            $errors[] = [
                'key' => Config::createErrorMessage(__METHOD__, __LINE__, $t),
                'domain' => 'Modules.Drsoftfrvalidatecustomer.Error',
                'parameters' => [],
            ];
        }

        return $errors;
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function removeConfiguration(): void
    {
        foreach (self::CONFIGURATION_KEYS as $key) {
            $this->configuration->remove($key);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function validateConfiguration(array $configuration): bool
    {
        if (null === $this->validator) {
            return true;
        }

        return $this->validator->validate($configuration);
    }
}
