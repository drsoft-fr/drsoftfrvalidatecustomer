<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShop\PrestaShop\Core\Domain\ValueObject\Email as EmployeeEmail;
use PrestaShopBundle\Form\Admin\Type\EmailType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class SettingType
 *
 * This class represents a form type for managing settings.
 * It extends the TranslatorAwareType class for translation support.
 */
final class SettingType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('admin_action_customer_account_add_email', EmailType::class, [
                'constraints' => [
                    $this->getLengthConstraint(),
                    new CleanHtml(),
                    new Email([
                        'message' => $this->trans(
                            '%s is invalid.',
                            'Admin.Notifications.Error'
                        ),
                    ]),
                ],
                'empty_data' => '',
                'label' => $this->trans('Email address', 'Admin.Global'),
                'help' => $this->trans('Emails will be sent to this address.', 'Admin.Shopparameters.Help'),
                'required' => false,
            ])
            ->add('admin_send_email_on_action_customer_account_add_hook', SwitchType::class, [
                'empty_data' => false,
                'help' => $this->trans(
                    'Would you like to receive an e-mail when a user creates an account?',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'Send an email on "actionCustomerAccountAdd" hook?',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'required' => true,
            ])
            ->add('enable_email_approval', SwitchType::class, [
                'empty_data' => false,
                'help' => $this->trans(
                    'Enable to send customer approval after validation.',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'Enable email approval',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'required' => true,
            ])
            ->add('enable_email_pending_approval', SwitchType::class, [
                'empty_data' => false,
                'help' => $this->trans(
                    'Enable to send customer a pending email for account.',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'Enable email pending',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'required' => true,
            ]);
    }

    /**
     * Returns a Length constraint object for validating the length of a field.
     *
     * @return Length The Length constraint object.
     */
    private function getLengthConstraint(): Length
    {
        $options = [
            'max' => EmployeeEmail::MAX_LENGTH,
            'maxMessage' => $this->trans(
                'This field cannot be longer than %limit% characters',
                'Admin.Notifications.Error',
                ['%limit%' => EmployeeEmail::MAX_LENGTH]
            ),
        ];

        return new Length($options);
    }
}
