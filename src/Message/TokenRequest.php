<?php

namespace Omnipay\Santander\Message;

/**
 * Quickpay Abstract Request
 */
class TokenRequest extends AbstractRequest
{
    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/oauth/v2/token';
    }

    /**
     * @return array|mixed
     */
    public function getData()
    {
        return array(
            'grant_type' => 'http://www.payever.de/api/payment',
            'scope' => 'API_CREATE_PAYMENT',
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getSecret()
        );
    }

    /**
     * @param mixed $data
     * @return mixed|TokenResponse
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

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            array('Accept' => 'application/json'),
            $data
        );

        $httpResponse = $httpRequest->send();
        // Empty response body should be parsed also as and empty array
        $body = $httpResponse->getBody(true);
        $jsonToArrayResponse = !empty($body) ? $httpResponse->json() : array();
        return $this->response = new TokenResponse($this, $jsonToArrayResponse, $httpResponse->getStatusCode());
    }
}
