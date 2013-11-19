<?php

namespace Sift;

/**
 * An indicator of a user's fraud status. Construct a label with one of the
 * factory methods, then submit the label using `Sift\Client::labelUser()`.
 */
class Label extends Payload
{
    const REASON_CHARGEBACK = '$chargeback';
    const REASON_SPAM = '$spam';
    const REASON_FUNNELING = '$funneling';
    const REASON_FAKE = '$fake';
    const REASON_REFERRAL = '$referral';
    const REASON_DUPLICATE_ACCOUNT = '$duplicate_account';

    /**
     * Create and return a label that identifies a user as non-fraudulent. This
     * is used to correct the Sift Science learning model when it incorrectly
     * identifies a user as fraudulent.
     *
     * @param string $description optional human-readable explanation
     */
    public static function good($description = null)
    {
        $labelData = array('$is_bad' => false);

        if ($description) {
            $labelData['$description'] = $description;
        }

        return new self($labelData);
    }

    /**
     * Create and return a label that identifies a user as fraudulent. This is
     * used to train the Sift Science learning model.
     *
     * @param array  $reasons     optional array of reason codes
     * @param string $description optional human-readable explanation
     */
    public static function bad(array $reasons = null, $description = null)
    {
        $labelData = array('$is_bad' => true);

        if ($reasons) {
            $labelData['$reasons'] = $reasons;
        }
        if ($description) {
            $labelData['$description'] = $description;
        }

        return new self($labelData);
    }
}
