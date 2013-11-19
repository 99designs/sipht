<?php

namespace Sift\Tests;

use Sift\Event;

class EventTest extends SiftTestCase
{
    public function testCreateOrderEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$create_order',
                'foo' => 'bar',
            ),
            Event::createOrderEvent(array('foo' => 'bar'))
        );
    }

    public function testTransactionEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$transaction',
                'foo' => 'bar',
            ),
            Event::transactionEvent(array('foo' => 'bar'))
        );
    }

    public function testCreateAccountEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$create_account',
                'foo' => 'bar',
            ),
            Event::createAccountEvent(array('foo' => 'bar'))
        );
    }

    public function testUpdateAccountEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$update_account',
                'foo' => 'bar',
            ),
            Event::updateAccountEvent(array('foo' => 'bar'))
        );
    }

    public function testAddItemToCartEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$add_item_to_cart',
                'foo' => 'bar',
            ),
            Event::addItemToCartEvent(array('foo' => 'bar'))
        );
    }

    public function testRemoveItemFromCartEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$remove_item_from_cart',
                'foo' => 'bar',
            ),
            Event::removeItemFromCartEvent(array('foo' => 'bar'))
        );
    }

    public function testSubmitReviewEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$submit_review',
                'foo' => 'bar',
            ),
            Event::submitReviewEvent(array('foo' => 'bar'))
        );
    }

    public function testSendMessageEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$send_message',
                'foo' => 'bar',
            ),
            Event::sendMessageEvent(array('foo' => 'bar'))
        );
    }

    public function testLoginEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$login',
                'foo' => 'bar',
            ),
            Event::loginEvent(array('foo' => 'bar'))
        );
    }

    public function testLogoutEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$logout',
                'foo' => 'bar',
            ),
            Event::logoutEvent(array('foo' => 'bar'))
        );
    }

    public function testLinkSessionToUserEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => '$link_session_to_user',
                'foo' => 'bar',
            ),
            Event::linkSessionToUserEvent(array('foo' => 'bar'))
        );
    }

    public function testCustomEvent()
    {
        $this->assertEqualAssociativeArrays(
            array(
                '$type' => 'some_event',
                'foo' => 'bar',
            ),
            Event::customEvent('some_event', array('foo' => 'bar'))
        );
    }
}
