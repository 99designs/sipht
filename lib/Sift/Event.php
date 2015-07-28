<?php

namespace Sift;

/**
 * A record of some interesting user activity. Construct an event with one of
 * the factory methods, then submit the event using `Sift\Client::postEvent()`.
 *
 * @see https://siftscience.com/docs/references/events-api
 * @see Sift\Client::postEvent()
 */
class Event extends Payload
{
    const TYPE_CREATE_ORDER          = '$create_order';
    const TYPE_TRANSACTION           = '$transaction';
    const TYPE_CREATE_ACCOUNT        = '$create_account';
    const TYPE_UPDATE_ACCOUNT        = '$update_account';
    const TYPE_ADD_ITEM_TO_CART      = '$add_item_to_cart';
    const TYPE_REMOVE_ITEM_FROM_CART = '$remove_item_from_cart';
    const TYPE_SUBMIT_REVIEW         = '$submit_review';
    const TYPE_SEND_MESSAGE          = '$send_message';
    const TYPE_LOGIN                 = '$login';
    const TYPE_LOGOUT                = '$logout';
    const TYPE_LINK_SESSION_TO_USER  = '$link_session_to_user';

    // $transaction_type values (used in $transaction events)
    const TRANSACTION_TYPE_SALE      = '$sale';
    const TRANSACTION_TYPE_AUTHORIZE = '$authorize';
    const TRANSACTION_TYPE_CAPTURE   = '$capture';
    const TRANSACTION_TYPE_VOID      = '$void';
    const TRANSACTION_TYPE_REFUND    = '$refund';

    // $transaction_status values (used in $transaction events)
    const TRANSACTION_STATUS_SUCCESS = '$success';
    const TRANSACTION_STATUS_FAILURE = '$failure';
    const TRANSACTION_STATUS_PENDING = '$pending';

    // $payment_type values (used in nested $payment_method)
    const PAYMENT_TYPE_CREDIT_CARD              = '$credit_card';
    const PAYMENT_TYPE_ELECTRONIC_FUND_TRANSFER = '$electronic_fund_transfer';
    const PAYMENT_TYPE_STORE_CREDIT             = '$store_credit';
    const PAYMENT_TYPE_GIFT_CARD                = '$gift_card';
    const PAYMENT_TYPE_POINTS                   = '$points';
    const PAYMENT_TYPE_FINANCING                = '$financing';
    const PAYMENT_TYPE_THIRD_PARTY_PROCESSOR    = '$third_party_processor';

    // $payment_gateway values (used in nested $payment_method)
    const PAYMENT_GATEWAY_STRIPE          = '$stripe';
    const PAYMENT_GATEWAY_BRAINTREE       = '$braintree';
    const PAYMENT_GATEWAY_PAYPAL          = '$paypal';
    const PAYMENT_GATEWAY_AMAZON_PAYMENTS = '$amazon_payments';
    const PAYMENT_GATEWAY_ADYEN           = '$adyen';
    const PAYMENT_GATEWAY_WORLDPAY        = '$worldpay';
    const PAYMENT_AUTHORIZENET            = '$authorizenet';

    // $login_status values (used in $login events)
    const LOGIN_STATUS_SUCCESS = '$success';
    const LOGIN_STATUS_FAILURE = '$failure';

    /**
     * Create and return a $create_order event. This event should be used when
     * a user registers an intention to make a purchase.
     *
     * @see https://siftscience.com/docs/references/events-api#event-create-order
     * @param array $fields event data
     * @return Event
     */
    public static function createOrderEvent(array $fields)
    {
        return static::factory(self::TYPE_CREATE_ORDER, $fields);
    }

    /**
     * Create and return a $transaction event. This event should be used when an
     * exchange of money takes place.
     *
     * @see https://siftscience.com/docs/references/events-api#event-transaction
     * @param array $fields event data
     * @return Event
     */
    public static function transactionEvent(array $fields)
    {
        return static::factory(self::TYPE_TRANSACTION, $fields);
    }

    /**
     * Create and return a $create_account event. This event should be used when
     * a new user account is registered.
     *
     * @see https://siftscience.com/docs/references/events-api#event-create-account
     * @param array $fields event data
     * @return Event
     */
    public static function createAccountEvent(array $fields)
    {
        return static::factory(self::TYPE_CREATE_ACCOUNT, $fields);
    }

    /**
     * Create and return an $update_account event. This event should be used
     * when a user updates his or her account details.
     *
     * @see https://siftscience.com/docs/references/events-api#event-update-account
     * @param array $fields event data
     * @return Event
     */
    public static function updateAccountEvent(array $fields)
    {
        return static::factory(self::TYPE_UPDATE_ACCOUNT, $fields);
    }

    /**
     * Create and return an $add_item_to_cart event. This event should be used
     * when a user adds an item to his or her shopping cart.
     *
     * @see https://siftscience.com/docs/references/events-api#event-add-item-to-cart
     * @param array $fields event data
     * @return Event
     */
    public static function addItemToCartEvent(array $fields)
    {
        return static::factory(self::TYPE_ADD_ITEM_TO_CART, $fields);
    }

    /**
     * Create and return a $remove_item_from_cart event. This event should be
     * used when a user removes an item from his or her shopping cart.
     *
     * @see https://siftscience.com/docs/references/events-api#remove-item-from-cart
     * @param array $fields event data
     * @return Event
     */
    public static function removeItemFromCartEvent(array $fields)
    {
        return static::factory(self::TYPE_REMOVE_ITEM_FROM_CART, $fields);
    }

    /**
     * Create and return a $submit_review event. This event should be used when
     * a user submits a review of a product or user (e.g. seller).
     *
     * @see https://siftscience.com/docs/references/events-api#event-submit-review
     * @param array $fields event data
     * @return Event
     */
    public static function submitReviewEvent(array $fields)
    {
        return static::factory(self::TYPE_SUBMIT_REVIEW, $fields);
    }

    /**
     * Create and return a $send_message event. This event should be used when a
     * user sends a message to another user
     *
     * @see https://siftscience.com/docs/references/events-api#event-send-message
     * @param array $fields event data
     * @return Event
     */
    public static function sendMessageEvent(array $fields)
    {
        return static::factory(self::TYPE_SEND_MESSAGE, $fields);
    }

    /**
     * Create and return a $login event. This event should be used when a user
     * attemps to log in.
     *
     * @see https://siftscience.com/docs/references/events-api#event-login
     * @param array $fields event data
     * @return Event
     */
    public static function loginEvent(array $fields)
    {
        return static::factory(self::TYPE_LOGIN, $fields);
    }

    /**
     * Create and return a $logout event. This event should be used when a user
     * logs out.
     *
     * @see https://siftscience.com/docs/references/events-api#event-logout
     * @param array $fields event data
     * @return Event
     */
    public static function logoutEvent(array $fields)
    {
        return static::factory(self::TYPE_LOGOUT, $fields);
    }

    /**
     * Create and return a $link_session_to_user event. This event should be
     * used to associate an anonymous session with a user, e.g. in anonymous
     * checkout workflows
     *
     * @see https://siftscience.com/docs/references/events-api#event-link-session-to-user
     * @param array $fields event data
     * @return Event
     */
    public static function linkSessionToUserEvent(array $fields)
    {
        return static::factory(self::TYPE_LINK_SESSION_TO_USER, $fields);
    }

    /**
     * Create and return a custom event of the given type. A synonym for
     * `::factory()`.
     *
     * @see https://siftscience.com/docs/references/events-api#events-custom-events
     * @param string $type   event type
     * @param array  $fields event data
     * @return Event
     */
    public static function customEvent($type, array $fields)
    {
        return static::factory($type, $fields);
    }

    /**
     * Create an event of a given type, using the given fields.
     *
     * @param string $type   event type
     * @param array  $fields event data
     * @return Event
     */
    protected static function factory($type, array $fields)
    {
        $fields['$type'] = $type;
        return static::construct($fields);
    }

    /**
     * Create an event from an array of fields.
     *
     * @param array $fields event data
     * @return Event
     */
    public static function construct(array $fields)
    {
        return new static($fields);
    }
}
