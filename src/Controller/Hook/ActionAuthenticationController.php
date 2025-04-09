<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Controller\Hook;

use Customer;
use DrSoftFr\Module\ValidateCustomer\Config;
use DrSoftFr\PrestaShopModuleHelper\Controller\Hook\AbstractHookController;
use DrSoftFr\PrestaShopModuleHelper\Controller\Hook\HookControllerInterface;
use Exception;
use Throwable;

final class ActionAuthenticationController extends AbstractHookController implements HookControllerInterface
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * Checks if the data is valid.
     *
     * @return bool True if the data is valid, false otherwise.
     *
     * @throws Exception
     */
    private function checkData(): bool
    {
        if (
            empty($this->getContext()->customer)
        ) {
            return false;
        }

        if (!($this->getContext()->customer instanceof Customer)) {
            return false;
        }

        if (0 >= (int)$this->getContext()->customer->id) {
            return false;
        }

        return true;
    }

    /**
     * Handle the customer logout process.
     *
     * Logs out the current customer, handles alerts and redirection based on module settings.
     *
     * @param string $alert The alert message to be displayed upon logout.
     *
     * @return void
     *
     * @throws Exception
     */
    private function handleCustomerLogout(string $alert): void
    {
        $this->customer->logout();

        $this->getContext()->controller->errors[] = $alert;

        $link = $this
            ->getContext()
            ->link
            ->getPageLink('authentication');

        $this
            ->getContext()
            ->controller
            ->redirectWithNotifications($link);
    }

    /**
     * Handles an exception by logging an error message.
     *
     * @param Throwable $t The exception to handle.
     *
     * @return void
     */
    private function handleException(Throwable $t): void
    {
        $errorMessage = Config::createErrorMessage(__METHOD__, __LINE__, $t);

        $this->logger->error($errorMessage, [
            'error_code' => $t->getCode(),
            'object_type' => null,
            'object_id' => null,
            'allow_duplicate' => false,
        ]);
    }

    /**
     * Handles the manual validation of a customer account.
     *
     * - If the customer's SIRET is empty or manual validation is disabled, exits early.
     * - Retrieves the customer object using the AdapterCustomerRepository.
     * - Throws an AdapterCustomerNotFoundException if the customer object is not found.
     * - Checks if the customer is active and handles the logout if not active.
     *
     * @return void
     *
     * @throws Exception
     */
    private function handleManualValidationAccount(): void
    {
        if (true === (bool)$this->customer->active) {
            return;
        }

        $alert = $this->getContext()->getTranslator()->trans(
            'Your customer account has not yet been validated by our teams.',
            [],
            'Modules.Drsoftfrvalidatecustomer.Error'
        );

        $this->handleCustomerLogout($alert);
    }

    /**
     * Runs the execution of the method, handling exceptions and logging errors if necessary.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            if (false === $this->checkData()) {
                return;
            }

            $this->customer = $this->getContext()->customer;

            $this->handleManualValidationAccount();
        } catch (Throwable $t) {
            $this->handleException($t);
        }
    }
}
