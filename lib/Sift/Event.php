<?php

namespace Sift;

class Event extends \ArrayObject
{
    const TYPE_TRANSACTION = '$transaction';
    const TYPE_LABEL = '$label';

    /**
     * Create a transaction event using the given fields
     * @see https://siftscience.com/docs/rest-api#transactions
     * @param string $userId unique user identifier
     * @param array  $fields optional event data
     * @return Sift\Event
     */
    public static function transactionEvent($userId, $fields=array())
    {
        $fields['$type'] = self::TYPE_TRANSACTION;
        $fields['$user_id'] = $userId;
        return new self($fields);
    }

    /**
     * Create a label event using the given fields
     * @see https://siftscience.com/docs/rest-api#labels
     * @param string $label  label to apply to user
     * @param string $userId unique user identifier
     * @param array  $fields optional event data
     * @return Sift\Event
     */
    public static function labelEvent($label, $userId, $fields=array())
    {
        $fields['$type'] = self::TYPE_LABEL;
        $fields['$label'] = $label;
        $fields['$user_id'] = $userId;
        return new self($fields);
    }

    /**
     * Create a custom event using the given fields
     * @param string $type   event type
     * @param string $userId unique user identifier
     * @param array  $fields event data
     * @return Sift\Event
     */
    public static function customEvent($type, $userId, $fields=array())
    {
        $fields['$type'] = $type;
        $fields['$user_id'] = $userId;
        return new self($fields);
    }

    /**
     * Returns a copy of this event with an updated API key
     * @param string $apiKey
     * @return Sift\Event
     */
    public function withKey($apiKey)
    {
        $new = new self($this);
        $new['$api_key'] = $apiKey;
        return $new;
    }

    /**
     * Serialise as JSON
     * @return string
     * @throws Exception if serialisation fails
     */
    public function toJson()
    {
        $json = json_encode($this);
        if ($json === false) {
            throw new Exception(sprintf(
                'Unable to jsonify event: %s',
                print_r($this->withKey('<redacted>'), true)
            ));
        }
        return $json;
    }
}
