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

The following errors could be thrown by a client request:

 * `Sift\Exception\BadRequestException`: HTTP 40x; the request was rejected by
    the API

 * `Sift\Exception\ServerErrorException`: HTTP 50x; the API endpoint suffered
    some internal problem

 * `Sift\Exception\HttpException`: any other exception generated in the course
    of making the HTTP request (e.g. too many redirects)

 [1]: https://siftscience.com/docs/rest-api
