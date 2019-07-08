<?php

namespace Omnipay\Santander\Message;

/**
 * Quickpay Abstract Request
 */
class PurchaseRequest extends AbstractRequest
{
	/**
	 * @return string
	 */
	protected function getEndpoint()
	{
		return parent::getEndpoint() . '/api/payment';
	}

	/**
	 * @return false|string
	 */
	public function getCartItems()
	{
		return json_encode($this->getItems());
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setFirstName($value)
	{
		return $this->setParameter('firstName', $value);
	}

	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->getParameter('firstName');
	}


	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setLastName($value)
	{
		return $this->setParameter('lastName', $value);
	}

	/**
	 * @return mixed
	 */
	public function getLastName()
	{
		return $this->getParameter('lastName');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setEmail($value)
	{
		return $this->setParameter('email', $value);
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->getParameter('email');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setStreet($value)
	{
		return $this->setParameter('billingAddress1', $value);
	}

	/**
	 * @return mixed
	 */
	public function getStreet()
	{
		return $this->getParameter('billingAddress1');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setCity($value)
	{
		return $this->setParameter('billingCity', $value);
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->getParameter('billingCity');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setZip($value)
	{
		return $this->setParameter('billingPostcode', $value);
	}

	/**
	 * @return mixed
	 */
	public function getZip()
	{
		return $this->getParameter('billingPostcode');
	}

	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setCountry($value)
	{
		return $this->setParameter('billingCountry', $value);
	}


	/**
	 * @return mixed
	 */
	public function getCountry()
	{
		return $this->getParameter('billingCountry');
	}


	/**
	 * @param $value
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function setPhone($value)
	{
		return $this->setParameter('billingPhone', $value);
	}


	/**
	 * @return mixed
	 */
	public function getPhone()
	{
		return $this->getParameter('billingPhone');
	}

	/**
	 * @return array
	 */
	public function getSantanderParameters()
	{
		$params = array(
			"channel"     => $this->getChannel(),
			"amount"      => $this->getAmount(),
			"order_id"    => $this->getTransactionId(),
			"currency"    => $this->getCurrency(),
			"cart"        => $this->getCartItems(),
			"first_name"  => $this->getFirstName(),
			"last_name"   => $this->getLastName(),
			"street"      => $this->getStreet(),
			"zip"         => $this->getZip(),
			"city"        => $this->getCity(),
			"country"     => $this->getCountry(),
			"phone"       => $this->getPhone(),
			"email"       => $this->getEmail(),
//			"pending_url" => $this->getNotifyUrl(),
			"success_url" => $this->getReturnUrl(),
			"cancel_url"  => $this->getCancelUrl(),
			"failure_url" => $this->getCancelUrl(),
			"notice_url"  => $this->getNotifyUrl(),
		);

		return $params;
	}


	/**
	 * @return array|mixed
	 * @throws \Omnipay\Common\Exception\InvalidRequestException
	 */
	public function getData()
	{
		/*
		 * Check required fields before accessing the API.
		 * Required fields can also be found here: https://getpayever.com/developer/api-documentation/
		 */
		$this->validate('amount', 'channel', 'order_id', 'items');

		$data = $this->getSantanderParameters();
		return $data;
	}

	protected function createResponse($data)
	{
		return $this->response = new PurchaseResponse($this, $data);
	}
}
