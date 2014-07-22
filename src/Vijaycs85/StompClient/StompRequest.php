<?php

namespace Vijaycs85\StompClient;

/**
 * Class StompRequest
 */
class StompRequest {

  /**
   * @var array
   */
  public $headers;

  /**
   * @var null
   */
  protected $body;

  /**
   * @var
   */
  protected $requestQueue;

  /**
   * @var
   */
  protected $responseQueue;

  /**
   * @param array $headers
   * @param $body
   * @param array $queues
   */
  public function __construct(array $headers, $body = NULL, array $queues) {
    $queues += array(
      'request' => NULL,
      'response' => NULL,
    );
    $this->headers = $headers;
    $this->requestQueue = $queues['request'];
    $this->responseQueue = $queues['response'];
    $this->body = $body;
  }

  /**
   * @return mixed
   */
  public function getRequestQueue() {
    return $this->requestQueue;

  }

  /**
   * @return mixed
   */
  public function getResponseQueue() {
    return $this->responseQueue;
  }

  /**
   * @return mixed
   */
  public function getHeaders() {
    return $this->headers;

  }

  /**
   * @param $key
   *
   * @return null
   */
  public function getHeader($key) {
    if (isset($this->headers[$key])) {
      return $this->headers[$key];
    }
    return NULL;
  }

  /**
   * @return mixed
   */
  public function getBody() {
    return $this->body;
  }

}

