<?php

namespace Omnipay\Santander\Message;

/**
 * Quickpay Complete Request
 * It is used to check data from a callback and send the json body onwards
 */
class CompleteRequest extends AbstractRequest
{
	public function getData()
	{
		$data = $this->httpRequest->query->all();

		return $data;
	}

    /**
     * @codeCoverageIgnore
     */
    public function sendData($data)
    {
        return $this->response = new Response($this, $data);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getHttpMethod()
    {
    }
}

