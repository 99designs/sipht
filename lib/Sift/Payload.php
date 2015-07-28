<?php

namespace Sift;

/**
 * An array-like request payload that can be serialized to JSON.
 */
class Payload extends \ArrayObject
{
    /**
     * Returns a copy of this payload with an updated API key
     * @param string $apiKey
     * @return Event
     */
    public function withKey($apiKey)
    {
        $new = clone $this;
        $new['$api_key'] = $apiKey;
        return $new;
    }

    /**
     * Serialise to JSON
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

    public function equals($that)
    {
        return get_class($this) == get_class($that)
            && (array) $this == (array) $that;
    }
}
