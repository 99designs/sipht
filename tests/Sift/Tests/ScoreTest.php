<?php

namespace Sift\Tests;

use Sift\Score;

class ScoreTest extends SiftTestCase
{
    public function testFromArray()
    {
        $score = Score::fromArray(array(
            'user_id' => '123',
            'score' => 0.93,
            'reasons' => array(
                array(
                    'name' => 'UsersPerDevice',
                    'value' => 4,
                    'details' => array(
                        'users' => 'a, b, c, d',
                    ),
                ),
            ),
            'status' => 0,
            'error_message' => 'OK',
        ));

        $this->assertEquals('123', $score->userId);
        $this->assertEquals(0.93, $score->score);
        $this->assertEquals(
            array(
                array(
                    'name' => 'UsersPerDevice',
                    'value' => 4,
                    'details' => array(
                        'users' => 'a, b, c, d',
                    ),
                ),
            ),
            $score->reasons
        );
    }

    public function testFromArrayWithError()
    {
        $this->setExpectedException('Sift\Exception\ScoreException');
        Score::fromArray(array(
            'status' => 54,
            'error_message' => 'No events for specified user',
        ));
    }
}
