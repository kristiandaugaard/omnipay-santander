<?php

namespace Omnipay\Santander;

use Omnipay\Common\AbstractGateway;
use Omnipay\Omnipay;

/**
 * Santander Gateway
 */
class Gateway extends AbstractGateway
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Santander';
	}

	/**
	 * @return array
	 */
	public function getDefaultParameters()
	{
		parent::getDefaultParameters();

		return array(
			'grant_type' => 'http://www.payever.de/api/payment',
			'clientId'   => '',
			'channel'    => 'other_shopsystem',
			'secret'     => '',
			'token'      => '',
			'order_id'   => '',
			'scope'      => '',
			'testMode'   => false,
		);
	}


	/**
	 * @return mixed
	 */
	public function getGrantType()
	{
		return $this->getParameter('grant_type');
	}


	/**
	 * @param $value
	 * @return Gateway
	 */
	public function setGrantType($value)
	{
		return $this->setParameter('grant_type', $value);
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
	 * @return Gateway
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
	 * @param $value
	 * @return Gateway
	 */
	public function setSecret($value)
	{
		return $this->setParameter('secret', $value);
	}

	/**
	 * @return mixed
	 */
	public function getTokenExpires()
	{
		return $this->getParameter('tokenExpires');
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setTokenExpires($value)
	{
		return $this->setParameter('tokenExpires', $value);
	}

	/**
	 * @return bool
	 */
	public function hasToken()
	{
		$token = $this->getParameter('token');

		$expires = $this->getTokenExpires();
		if (!empty($expires) && !is_numeric($expires)) {
			$expires = strtotime($expires);
		}

		return !empty($token) && time() < $expires;
	}

	/**
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function createToken()
	{
		return $this->createRequest('\Omnipay\Santander\Message\TokenRequest', array());
	}

	/**
	 * @return mixed
	 */
	public function getToken($createIfNeeded = true)
	{
		if ($createIfNeeded && !$this->hasToken()) {
			$response = $this->createToken()->send();
			if ($response->isSuccessful()) {
				$data = $response->getData();
				if (isset($data['access_token'])) {
					$this->setToken($data['access_token']);
					$this->setTokenExpires(time() + $data['expires_in']);
				}
			}
		}
		return $this->getParameter('token');
	}

	/**
	 * @param $value
	 * @return Gateway
	 */
	public function setToken($value)
	{
		return $this->setParameter('token', $value);
	}

	/**
	 * @return mixed
	 */
	public function getOrderID()
	{
		return $this->getParameter('order_id');
	}

	/**
	 * @param $value
	 * @return Gateway
	 */
	public function setOrderID($value)
	{
		return $this->setParameter('order_id', $value);
	}

	/**
	 * @return mixed
	 */
	public function getScope()
	{
		return $this->getParameter('scope');
	}

	/**
	 * @param $value
	 * @return Gateway
	 */
	public function setScope($value)
	{
		return $this->setParameter('scope', $value);
	}

	/**
	 * @return bool|mixed
	 */
	public function getTestMode()
	{
		return $this->getParameter('testMode');
	}

	/**
	 * @param bool $value
	 * @return AbstractGateway|Gateway
	 */
	public function setTestMode($value)
	{
		return $this->setParameter('testMode', $value);
	}

	/**
	 * @param string $class
	 * @param array $parameters
	 * @return \Omnipay\Common\Message\AbstractRequest
	 */
	public function createRequest($class, array $parameters = array())
	{
		if (!$this->hasToken() && $class != '\Omnipay\Santander\Message\TokenRequest') {
			$this->getToken();
		}

		return parent::createRequest($class, $parameters);
	}

	/**
	 * Start a purchase request
	 *
	 * @param array $parameters array of options
	 * @return \Omnipay\Santander\Message\PurchaseRequest
	 */
	public function purchase(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\Santander\Message\PurchaseRequest', $parameters);
	}

	/**
	 * @param array $parameters
	 * @return \Omnipay\Santander\Message\VoidRequest
	 */
	public function void(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\Santander\Message\VoidRequest', $parameters);
	}

//	/**
//	 * @param array $parameters
//	 * @return \Omnipay\Santander\Message\RefundRequest
//	 */
//	public function refund(array $parameters = array())
//	{
//		return $this->createRequest('\Omnipay\Santander\Message\RefundRequest', $parameters);
//	}

//	/**
//	 * Is used for callbacks coming in to the system
//	 * notify will verify these callbacks and eventually return the body of the callback to the app
//	 * @param array $parameters
//	 * @return \Omnipay\Santander\Message\NotifyRequest
//	 */
//	public function notify(array $parameters = array())
//	{
//		return $this->createRequest('\Omnipay\Santander\Message\NotifyRequest', $parameters);
//	}

	/**
	 * A complete request
	 *
	 * @param array $parameters
	 * @return \Omnipay\Santander\Message\CompleteRequest
	 */
	public function completeRequest(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\Santander\Message\CompleteRequest', $parameters);
	}

	/**
	 * Complete a purchase
	 *
	 * @param array $parameters
	 * @return \Omnipay\Santander\Message\CompleteRequest
	 */
	public function completePurchase(array $parameters = array())
	{
		return $this->completeRequest($parameters);
	}

	/**
	 * Complete cancel
	 *
	 * @param array $parameters
	 * @return \Omnipay\Santander\Message\CompleteRequest
	 */
	public function completeVoid(array $parameters = array())
	{
		return $this->completeRequest($parameters);
	}
//
//	/**
//	 * Complete refund
//	 *
//	 * @param array $parameters
//	 * @return \Omnipay\Santander\Message\CompleteRequest
//	 */
//	public function completeRefund(array $parameters = array())
//	{
//		return $this->completeRequest($parameters);
//	}
}
