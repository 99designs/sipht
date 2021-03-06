A PHP client for the [Sift Science][1] REST API.

Note: Sift Science now offer their own PHP bindings [here][2].

[![Build Status](https://travis-ci.org/99designs/sipht.png)](https://travis-ci.org/99designs/sipht)

This client supports the three basic operations exposed by the Sift API: posting
events, labelling users and fetching scores.

The currently supported API version is **`v203`**.


## Instantiating a client

Interactions with the API occur via `Sift\Client`. You instantiate a client by
passing your API key to its constructor:
```php
$client = new Sift\Client('my-api-key');
```

The following errors may be thrown by any client request:

 * `Sift\Exception\BadRequestException`: HTTP 40x; the request was rejected by
    the API

 * `Sift\Exception\ServerErrorException`: HTTP 50x; the API endpoint suffered
    some internal problem

 * `Sift\Exception\HttpException`: any other exception generated in the course
    of making the HTTP request (e.g. too many redirects)


## Posting events

Create instances of `Sift\Event`, e.g.:
```php
$event = new Sift\Event(array(
    '$type' => Sift\Event::TYPE_TRANSACTION,
    '$user_id' => '1234',
    // ...
));
```

Alternatively, factory constructors are provided for each event type:
```php
$event = Sift\Event::transactionEvent(array(
    '$user_id' => '1234',
    // ...
));
```

Then post the event via `Sift\Client::postEvent()`:
```php
$response = $client->postEvent($event);
```

See https://siftscience.com/docs/references/events-api for more information on
posting event data.


## Labelling users

Create label objects using the factory methods of `Sift\Label`, e.g.:
```php
// Label a user as fraudulent, optionally specifying reason codes and an explanation:
$reasons = array(Sift\Label::REASON_SPAM);
$label = Sift\Label::bad($reasons, 'User engaged in phishing attack')

// Alternatively, correct a false positive by labelling a user as non-fraudulent:
$good = Sift\Label::good('Mistakenly identified as fraudulent');
```

Then post the event via `Sift\Client::labelUser()`:
```php
$response = $client->labelUser('some-user-id', $label);
```

See https://siftscience.com/docs/references/labels-api for more information on
labelling users.


## Fetching scores

Fetch fraud score data for a user via `Sift\Client::userScore()`:
```php
$score = $client->userScore('some-user-id');
```

This returns an instance of `Sift\Score`. Note that if no events have been
captured for the given user, a `Sift\Exception\ScoreException` will be thrown.

See https://siftscience.com/docs/getting-scores for more information on fetching
user fraud scores.

 [1]: https://siftscience.com/
 [2]: https://github.com/SiftScience/sift-php
