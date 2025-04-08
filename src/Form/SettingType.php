<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShop\PrestaShop\Core\Domain\ValueObject\Email as EmployeeEmail;
use PrestaShopBundle\Form\Admin\Type\EmailType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Translation\TranslatorInterface;
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
     * @var array
     */
    private $cmsPageChoices;

    public function __construct(
        TranslatorInterface $translator,
        array               $locales,
        array               $cmsPageChoices
    )
    {
        parent::__construct($translator, $locales);

        $this->cmsPageChoices = $cmsPageChoices;
    }

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
            ->add('cms_notify_id', ChoiceType::class, [
                'empty_data' => 0,
                'choices' => array_merge(['--' => 0], $this->cmsPageChoices),
                'choice_translation_domain' => false,
                'help' => $this->trans(
                    'CMS page ID where validate account waiting. Selecting nothing will disable redirection.',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'CMS page ID validate account waiting',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'multiple' => false,
                'required' => true,
            ])
            ->add('cms_not_activated_id', ChoiceType::class, [
                'empty_data' => 0,
                'choices' => array_merge(['--' => 0], $this->cmsPageChoices),
                'choice_translation_domain' => false,
                'help' => $this->trans(
                    'CMS page ID where the user is redirect if he try to login and his account are not longer enable. Selecting nothing will disable redirection.',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'CMS page ID account not enable.',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'multiple' => false,
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
            ])
            ->add('enable_unauthenticated_customer_alert', SwitchType::class, [
                'empty_data' => false,
                'help' => $this->trans(
                    'Would you like to display an alert to visitors who are not logged in?',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'Enable unauthenticated customer alert',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'required' => true,
            ])
            ->add('enable_unapproved_customer_alert', SwitchType::class, [
                'empty_data' => false,
                'help' => $this->trans(
                    'Would you like to display an alert to unapproved visitors?',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                ),
                'label' => $this->trans(
                    'Enable unapproved customer alert',
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
