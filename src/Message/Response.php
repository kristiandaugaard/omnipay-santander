<?php

namespace Omnipay\Santander\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{
	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		$body = $this->getResponseBody();

		if ($body) {
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
	public function getMessage(){
		if($this->getResponseBody()){
			$body = $this->getResponseBody();
			return isset($body['error_description']) ? $body['error_description']: '';
		}
		return null;
	}
} 
