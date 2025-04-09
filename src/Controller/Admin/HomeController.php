<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Controller\Admin;

use DrSoftFr\Module\ValidateCustomer\Data\Configuration\SettingConfiguration;
use drsoftfrvalidatecustomer;
use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class HomeController.
 *
 * @ModuleActivated(moduleName="drsoftfrvalidatecustomer", redirectRoute="admin_module_manage")
 */
final class HomeController extends FrameworkBundleAdminController
{
    const PAGE_INDEX_ROUTE = 'admin_drsoft_fr_validate_customer_home_index';
    const TAB_CLASS_NAME = 'AdminDrSoftFrValidateCustomerHome';
    const TEMPLATE_FOLDER = '@Modules/drsoftfrvalidatecustomer/views/templates/admin/home/';

    /**
     * Renders the index page of the drSoft.fr ValidateCustomer homes.
     *
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     redirectRoute="admin_module_manage",
     *     message="Access denied."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $form = $this
            ->getValidateCustomerFormHandler()
            ->getForm();

        return $this->render(self::TEMPLATE_FOLDER . 'index.html.twig', [
            'enableSidebar' => true,
            'drsoft_fr_validate_customer_setting_form' => $form->createView(),
            'help_link' => $this->generateSidebarLink($request->attributes->get('_legacy_controller')),
            'module' => $this->getModule(),
        ]);
    }

    /**
     * Reset home
     *
     * @AdminSecurity(
     *     "is_granted('update', request.get('_legacy_controller'))",
     *     redirectRoute="admin_drsoft_fr_validate_customer_home_index",
     *     message="You do not have permission to reset this."
     * )
     *
     * @return RedirectResponse
     */
    public function resetAction(): RedirectResponse
    {
        try {
            $this
                ->getSettingConfiguration()
                ->initConfiguration();

            $this->addFlash(
                'success',
                $this->trans(
                    'The default setting are reset.',
                    'Modules.Drsoftfrvalidatecustomer.Admin'
                )
            );
        } catch (Throwable $t) {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot reset the setting. Exception: #%code% - %message%',
                    'Modules.Drsoftfrvalidatecustomer.Error',
                    [
                        '%code%' => $t->getCode(),
                        '%message%' => $t->getMessage(),
                    ]
                )
            );
        }

        return $this->redirectToRoute(self::PAGE_INDEX_ROUTE);
    }

    /**
     * Edit home
     *
     * @AdminSecurity(
     *     "is_granted('update', request.get('_legacy_controller'))",
     *     redirectRoute="admin_drsoft_fr_validate_customer_home_index",
     *     message="You do not have permission to edit this."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function saveAction(Request $request): Response
    {
        try {
            $handler = $this->getValidateCustomerFormHandler();

            $form = $handler->getForm();
            $form->handleRequest($request);

            if (!$form->isSubmitted()) {
                return $this->redirectToRoute(self::PAGE_INDEX_ROUTE);
            }

            if (!$form->isValid()) {
                $this->addFlash(
                    'error',
                    $this->trans(
                        'The form is invalid.',
                        'Modules.Drsoftfrvalidatecustomer.Error'
                    )
                );

                return $this->redirectToRoute(self::PAGE_INDEX_ROUTE);
            }

            $errors = $handler->save($form->getData());

            if (!empty($errors)) {
                $this->flashErrors($errors);
            } else {
                $this->addFlash(
                    'success',
                    $this->trans(
                        'Your setting are saved.',
                        'Modules.Drsoftfrvalidatecustomer.Success'
                    )
                );
            }

        } catch (Throwable $t) {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot save the setting. Throwable: #%code% - %message%',
                    'Modules.Drsoftfrvalidatecustomer.Error',
                    [
                        '%code%' => $t->getCode(),
                        '%message%' => $t->getMessage(),
                    ]
                )
            );
        }

        return $this->redirectToRoute(self::PAGE_INDEX_ROUTE);
    }

    /**
     * @return drsoftfrvalidatecustomer
     */
    protected function getModule(): drsoftfrvalidatecustomer
    {
        /** @type drsoftfrvalidatecustomer */
        return $this->get('drsoft_fr.module.validate_customer.module');
    }

    /**
     * Get ValidateCustomer configuration.
     *
     * @return SettingConfiguration
     */
    protected function getSettingConfiguration(): SettingConfiguration
    {
        /** @type SettingConfiguration */
        return $this->get('drsoft_fr.module.validate_customer.data.configuration.setting_configuration');
    }

    /**
     * Get ValidateCustomer form handler.
     *
     * @return FormHandlerInterface
     */
    protected function getValidateCustomerFormHandler(): FormHandlerInterface
    {
        /** @type FormHandlerInterface */
        return $this->get('drsoft_fr.module.validate_customer.form.handler.setting_form_handler');
    }
}
