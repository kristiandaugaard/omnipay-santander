<?php

namespace Omnipay\Santander\Message;

class TokenResponse extends Response
{
	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		$body = $this->getResponseBody();

		if (isset($body['access_token'])) {
			return true;
		}

		return false;
	}

	/**
	 * Santander will return a json object as the body
	 * @return bool|mixed
	 */
	public function getResponseBody(){
		return $this->data;
	}
} 
