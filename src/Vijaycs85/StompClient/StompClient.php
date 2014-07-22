<?php
namespace Vijaycs85\StompClient;

/**
 * Class StompClient
 */
class StompClient {

  /**
   * @var
   */
  protected $stomp;

  /**
   * Default timeout is 5 sec.
   *
   * @var int
   */
  protected $timeout = 5;

  /**
   * @var array
   */
  protected $responseSelector = array();

  /**
   * @param $stomp
   */
  public function __construct($stomp) {
    $this->stomp = $stomp;
  }

  /**
   * @param $timeout
   */
  public function setTimeout($timeout) {
    $this->timeout = $timeout;
  }

  /**
   * @param StompRequest $request
   *
   * @return mixed
   */
  public function getResponse(StompRequest $request) {
    $correlation_id  = $this->getCorrelationId();
    // Works for JMS like ActiveMQ.
    $this->addResponseSelector('JMSCorrelationID', $correlation_id);
    // append STOMP related headers
    $request->headers += array(
      'correlation-id' => $correlation_id,
      'reply-to' => $request->getResponseQueue(),
    );
    $this->stomp->send($request->getRequestQueue(), $request->getBody(), $request->getHeaders());
    return $this->getResponseFrame($request, $this->getResponseSelector());
  }

  /**
   * @param $key
   * @param $value
   */
  public function addResponseSelector($key, $value) {
    $this->responseSelector[$key] = $value;
  }

  /**
   * @return string
   */
  protected function getResponseSelector()  {
    $selector = '';
    foreach ($this->responseSelector as $key => $value) {
      $selector .= "$key='$value'";
    }
    return $selector;
  }

  /**
   * @param StompRequest $request
   * @param $selector
   *
   * @return mixed
   */
  protected function getResponseFrame(StompRequest $request, $selector) {
    $this->stomp->subscribe($request->getResponseQueue(), array('selector' => $selector));
    $request_start_at = microtime(TRUE);

    while(TRUE) {
      if ($this->stomp->hasFrame()) {
        // read response from the response queue
        if ($response = $this->stomp->readFrame()) {
          $this->stomp->ack($response);
        }
        break;
      }

      $time_spent = microtime(TRUE) - $request_start_at;
      if ($time_spent > self::TIMEOUT) {
        // timeout reached
        break;
      }
    }
    $this->stomp->unsubscribe($request->getResponseQueue(), array('selector' => $selector));

    return $response;
  }

  /**
   * @return string
   */
  protected function getCorrelationId() {
    return uniqid('DEV-', FALSE);
  }

}
