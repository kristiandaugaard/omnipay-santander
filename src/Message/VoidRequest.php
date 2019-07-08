<?php


namespace Omnipay\Santander\Message;

/**
 * Santander Cancel Request
 */
class VoidRequest extends AbstractRequest
{
	public function __construct($httpClient, $httpRequest)
	{
		parent::__construct($httpClient, $httpRequest);
	}

	public function getData()
	{
		$data = array(
			'id' => $this->getTransactionReference()
		);
		return $data;
	}
}
