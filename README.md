StompClient
===========

Simple STOMP to JMQ server

Example:
-------

<code>
// Custom headers to queue server or external service provider.
$headers = array(
  'CustomHeader1' => 'Custom header value',
);
// Request & response queue location.
$queues = array(
  'request' => '/queue/com.domain.service.request',
  'response' => '/queue/com.domain.service.response',
);
// Request body string.
$body = '<requestBody>foo</requestBody>';

// Message queue server URI.
$url = parse_url('tcp://example.com:61616');

$request = new StompRequest($headers, $body, $queues);
$stomp = new Stomp($url['scheme'] . '://' . $url['host'] . ':' . $url['port'], isset($url['user'])?$url['user']:NULL, isset($url['pass'])?$url['pass']:NULL);

$stomp_client = new StompClient($stomp);
$response = $stomp_client->getResponse($request);

unset($stomp);
print_r($response);
</code>

Output:
-------
<code>
StompFrame Object
(
    [command] => MESSAGE
    [headers] => Array
        (
            [message-id] => ID:394292-sample.mqserver.com-45964-1405009274970-0:1:8:1:15
            [breadcrumbId] => ID:394292-sample.mqserver.com-37346-1399540645810-2:91707:-1:1:1
            [CustomHeader1] => Custom header value
            [destination] => /queue/com.domain.service.response
            [timestamp] => 1406031094126
            [expires] => 0
            [priority] => 4
            [reply-to] => /queue/com.domain.service.response
        )

    [body] => <?xml version="1.0" encoding="UTF-8"?><ResponseBody></ResponseBody>
)
</code>


