<?php

namespace Omnipay\Santander\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Quickpay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @var string
     */
    protected $liveEndpoint = 'https://mein.payever.de';

    /**
     * @var string
     */
    protected $testEndpoint = 'https://sandbox.payever.de';
//
//    protected $tokenEndpoint = '/oauth/v2/token';


	/**
	 * @return string
	 */
	protected function getEndpoint()
	{
		$base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
		return $base;
	}

//	/**
//	 * @return string
//	 */
//	protected function getTokenEndpoint()
//	{
//		$base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
//		return $base . $this->tokenEndpoint;
//	}



	/**
	 * @return mixed|string
	 */
	public function getToken()
	{
		return $this->getParameter('token');
	}

	/**
	 * @param string $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setToken($value)
	{
		return $this->setParameter('token', $value);
	}

	/**
	 * @return mixed
	 */
	public function getClientId()
	{
		return $this->getParameter('clientId');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setClientId($value)
	{
		return $this->setParameter('clientId', $value);
	}

	/**
	 * @return mixed
	 */
	public function getSecret()
	{
		return $this->getParameter('secret');
	}

	/**
	 * @return mixed
	 */
	public function getChannel()
	{
		return $this->getParameter('channel');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setChannel($value)
	{
		return $this->setParameter('channel', $value);
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setSecret($value)
	{
		return $this->setParameter('secret', $value);
	}

	/**
	 * @return string
	 */
	public function getOrderID()
	{
		return $this->getParameter('order_id');
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setOrderID($value)
	{
		return $this->setParameter('order_id', $value);
	}

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @param $data
     * @return mixed
     */
    public function sendData($data)
    {
		// don't throw exceptions for 4xx errors
		$this->httpClient->getEventDispatcher()->addListener(
			'request.error',
			function ($event) {
				if ($event['response']->isClientError()) {
					$event->stopPropagation();
				}
			}
		);
		// Guzzle HTTP Client createRequest does funny things when a GET request
		// has attached data, so don't send the data if the method is GET.
		if ($this->getHttpMethod() == 'GET') {
			$httpRequest = $this->httpClient->createRequest(
				$this->getHttpMethod(),
				$this->getEndpoint() . '?' . http_build_query($data),
				array(
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $this->getToken(),
					'Content-type' => 'application/json',
				)
			);
		} else {
			$httpRequest = $this->httpClient->createRequest(
				$this->getHttpMethod(),
				$this->getEndpoint(),
				array(
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $this->getToken(),
					'Content-type' => 'application/json',
				),
				$this->toJSON($data)
			);
		}

		try {
			$httpRequest->getCurlOptions()->set(CURLOPT_SSLVERSION, 6); // CURL_SSLVERSION_TLSv1_2 for libcurl < 7.35
			$httpResponse = $httpRequest->send();
			// Empty response body should be parsed also as and empty array
			$body = $httpResponse->getBody(true);
			$jsonToArrayResponse = !empty($body) ? $httpResponse->json() : array();
			return $this->response = $this->createResponse($jsonToArrayResponse);
		} catch (\Exception $e) {
			throw new InvalidResponseException(
				'Error communicating with payment gateway: ' . $e->getMessage(),
				$e->getCode()
			);
		}
    }

	/**
	 * @param $data
	 * @param int $options
	 * @return false|mixed|string
	 */
	public function toJSON($data, $options = 0)
	{
		// Because of PHP Version 5.3, we cannot use JSON_UNESCAPED_SLASHES option
		// Instead we would use the str_replace command for now.
		// TODO: Replace this code with return json_encode($this->toArray(), $options | 64); once we support PHP >= 5.4
		if (version_compare(phpversion(), '5.4.0', '>=') === true) {
			return json_encode($data, $options | 64);
		}
		return str_replace('\\/', '/', json_encode($data, $options));
	}

	/**
	 * @param $data
	 * @return Response
	 */
	protected function createResponse($data)
	{
		return $this->response = new Response($this, $data);
	}
}
