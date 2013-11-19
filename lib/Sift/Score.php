<?php

namespace Sift;

use Sift\Exception\ScoreException;

/**
 * User score data, produced in response to a score API call
 * @see https://siftscience.com/docs/getting-scores/
 */
class Score
{
    public $userId;
    public $score;
    public $reasons;

    /**
     * Constructs a score from an array of score data. Throws an exception if
     * $data specifies a non-zero 'status' property.
     *
     * @param array $data
     * @return Sift\Score
     * @throws Sift\Exception\ScoreException
     */
    public static function fromArray(array $data)
    {
        if (!empty($data['status'])) {
            throw new ScoreException($data['error_message'], $data['status']);
        }

        return new self(
            $data['user_id'],
            $data['score'],
            $data['reasons']
        );
    }

    public function __construct($userId, $score, array $reasons)
    {
        $this->userId = $userId;
        $this->score = $score;
        $this->reasons = $reasons;
    }
}
