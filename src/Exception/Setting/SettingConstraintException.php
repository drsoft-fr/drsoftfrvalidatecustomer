<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Exception\Setting;

/**
 * Thrown when Setting constraints are violated
 */
class SettingConstraintException extends SettingException
{
    /**
     * When admin_action_customer_account_add_email field is invalid
     */
    public const INVALID_ADMIN_ACTION_CUSTOMER_ACCOUNT_ADD_EMAIL = 20;

    /**
     * When admin_send_email_on_action_customer_account_add_hook field is invalid
     */
    public const INVALID_ADMIN_EMAIL_ON_ACTION_CUSTOMER_ACCOUNT_ADD = 30;

    /**
     * When cms_notify_id field is invalid
     */
    public const INVALID_CMS_NOTIFY_ID = 40;

    /**
     * When cms_not_activated_id field is invalid
     */
    public const INVALID_CMS_NOT_ACTIVATED_ID = 50;

    /**
     * When enable_email_approval field is invalid
     */
    public const INVALID_ENABLE_EMAIL_APPROVAL = 80;

    /**
     * When enable_email_pending_approval field is invalid
     */
    public const INVALID_ENABLE_EMAIL_PENDING_APPROVAL = 90;

    /**
     * When enable_unauthenticated_customer_alert field is invalid
     */
    public const INVALID_ENABLE_UNAUTHENTICATED_CUSTOMER_ALERT = 130;

    /**
     * When enable_unapproved_customer_alert field is invalid
     */
    public const INVALID_ENABLE_UNAPPROVED_CUSTOMER_ALERT = 140;
}
