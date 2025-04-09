<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Data\Validator;

use Exception;
use DrSoftFr\Module\ValidateCustomer\Exception\Setting\SettingConstraintException;
use DrSoftFr\PrestaShopModuleHelper\Data\Validator\AbstractValidator;
use DrSoftFr\PrestaShopModuleHelper\Data\Validator\ValidatorInterface;

final class SettingValidator extends AbstractValidator implements ValidatorInterface
{
    /**
     * Validates all the data fields.
     *
     * @param array $data The data array to validate.
     *
     * @return bool Returns true if all the fields pass the validation.
     *
     * @throws SettingConstraintException If any of the data fields fail validation.
     */
    public function validate(array $data): bool
    {
        $this
            ->validateAdminSendEmailOnActionCustomerAccountAddHook($data)
            ->validateAdminActionCustomerAccountAddEmail($data)
            ->validateEnableEmailApproval($data)
            ->validateEnableEmailPendingApproval($data);

        return true;
    }

    /**
     * Validates the admin_action_customer_account_add_email field in the configuration array if admin_send_email_on_action_customer_account_add_hook is true.
     * Ensures that the field is set, not empty, and is a string value.
     *
     * @param array $configuration The configuration array to validate.
     *
     * @return SettingValidator
     *
     * @throws SettingConstraintException If admin_send_email_on_action_customer_account_add_hook is true but admin_action_customer_account_add_email field is not set.
     * @throws SettingConstraintException If admin_send_email_on_action_customer_account_add_hook is true but admin_action_customer_account_add_email field is empty.
     * @throws SettingConstraintException If admin_send_email_on_action_customer_account_add_hook is true but admin_action_customer_account_add_email field is not a string.
     * @throws Exception
     */
    private function validateAdminActionCustomerAccountAddEmail(array $configuration): SettingValidator
    {
        if (!$configuration['admin_send_email_on_action_customer_account_add_hook']) {
            return $this;
        }

        $this->isSet($configuration, 'admin_action_customer_account_add_email', new SettingConstraintException);
        $this->isEmpty($configuration, 'admin_action_customer_account_add_email', new SettingConstraintException);
        $this->isString($configuration, 'admin_action_customer_account_add_email', new SettingConstraintException);

        return $this;
    }

    /**
     * Validates the admin_send_email_on_action_customer_account_add_hook field in the configuration array.
     * Ensures that the field is set and is a boolean value.
     *
     * @param array $configuration The configuration array to validate.
     *
     * @return SettingValidator
     *
     * @throws SettingConstraintException If the admin_send_email_on_action_customer_account_add_hook field is not set.
     * @throws SettingConstraintException If the admin_send_email_on_action_customer_account_add_hook field is not a boolean value.
     * @throws Exception
     */
    private function validateAdminSendEmailOnActionCustomerAccountAddHook(array $configuration): SettingValidator
    {
        $this->isSet($configuration, 'admin_send_email_on_action_customer_account_add_hook', new SettingConstraintException);
        $this->isBool($configuration, 'admin_send_email_on_action_customer_account_add_hook', new SettingConstraintException);

        return $this;
    }

    /**
     * Validates the enable_email_approval field in the configuration array.
     * Ensures that the field is set and is a boolean value.
     *
     * @param array $configuration The configuration array to validate.
     *
     * @return SettingValidator
     *
     * @throws SettingConstraintException If the enable_email_approval field is not set.
     * @throws SettingConstraintException If the enable_email_approval field is not a boolean value.
     * @throws Exception
     */
    private function validateEnableEmailApproval(array $configuration): SettingValidator
    {
        $this->isSet($configuration, 'enable_email_approval', new SettingConstraintException);
        $this->isBool($configuration, 'enable_email_approval', new SettingConstraintException);

        return $this;
    }

    /**
     * Validates the enable_email_pending_approval field in the configuration array.
     * Ensures that the field is set and is a boolean value.
     *
     * @param array $configuration The configuration array to validate.
     *
     * @return SettingValidator
     *
     * @throws SettingConstraintException If the enable_email_pending_approval field is not set.
     * @throws SettingConstraintException If the enable_email_pending_approval field is not a boolean value.
     * @throws Exception
     */
    private function validateEnableEmailPendingApproval(array $configuration): SettingValidator
    {
        $this->isSet($configuration, 'enable_email_pending_approval', new SettingConstraintException);
        $this->isBool($configuration, 'enable_email_pending_approval', new SettingConstraintException);

        return $this;
    }
}
