A PHP client for the [Sift Science REST API][1].

Create instances of `Sift\Event` using factory methods:

```php
use Sift\Event;

// Create an event with '$type' == '$transaction':
$transactionEvent = Event::transactionEvent(array(
    '$user_id' => '1234',
    '$amount' => 1230000,
    // ...
));

// Create an event with '$type' == '$label':
$labelEvent = Event::labelEvent(array(
    '$user_id' => '1234',
    '$label' => '$ban',
    // ...
));

// Create some custom event:
$fooEvent = Event::customEvent('foo', array(
    '$user_id' => '1234',
    'some_key' => 'some_value',
    // ...
));
```

Then post them using a instance of `Sift\Client`:

```php
use Sift\Client;

$apiKey = 'abc123';
$client = new Client($apiKey);
$response = $client->postEvent($event);
```

[Guzzle][2] is used under the hood. If you need to handle errors, catch
`GuzzleException` and friends.

 [1]: https://siftscience.com/docs/rest-api
 [2]: http://guzzlephp.org/
