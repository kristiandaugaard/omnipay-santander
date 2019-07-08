<?php

namespace Omnipay\Santander\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends Response implements RedirectResponseInterface
{
	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		$body = $this->getResponseBody();

		if (isset($body['redirect_url'])) {
			return true;
		}

		return false;
	}

	/**
	 * Santander will return a json object as the body
	 * @return bool|mixed
	 */
	public function getResponseBody(){
		if($this->data){
			// JSON is valid
			return $this->data;
		}
		return false;
	}

	/**
	 * @return null|string
	 */
	public function getTransactionReference()
	{
		if ($this->getResponseBody()) {
			$body = $this->getResponseBody();
		}
		return isset($body['call']['id']) ? $body['call']['id'] : '';
	}

	/**
	 * @return bool
	 */
	public function isRedirect()
	{
		return true;
	}

	public function getRedirectUrl()
	{
		$data = $this->getResponseBody();
		return $data['redirect_url'];
	}

	/**
	 * @return string
	 */
	public function getRedirectMethod()
	{
		return 'GET';
	}

	/**
	 * @return array
	 * @codeCoverageIgnore
	 */
	public function getRedirectData()
	{
		return [];
	}
} 
