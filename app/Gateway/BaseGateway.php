<?php

namespace App\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

abstract class BaseGateway implements IBaseGateway
{

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected  $baseUrl;

    /**
     * @var
     */
    protected $endPoint;

    /**
     * @var
     */
    protected $method;

    /**
     * @var
     */
    protected $params;

    /**
     * @var
     */
    protected $response;

    /**
     * @var
     */
    protected $token;

    /**
     * @var string
     */
    protected $requestOption = 'json';

    /**
     * MyPostGateway constructor.
     */
    public function __construct()
    {

        /**
         * Set gateway base credentials.
         */
        $this->setBaseCredentials();

        $this->response = [
            'status'    => true,
            'message'   => '',
            'data'      => ''
        ];
    }

    /**
     * @return mixed
     */
    abstract protected function setBaseCredentials();

    /**
     * Get response.
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Do request in Logistic.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function doRequest()
    {

        try {
            $client = new Client([
                'verify' => false
            ]);

            $response = $client->request($this->method, $this->baseUrl . $this->endPoint, [
                'headers' => [
                    'Authorization' => $this->token,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                $this->requestOption => $this->params
            ]);

            $this->response['data'] = $response->getBody()->getContents();

        } catch (ClientException $ex) {

            $response = json_decode($ex->getResponse()->getBody()->getContents(),true);

            Log::error('Error Client Gateway ', ['data' => $this->params, 'url' => $this->baseUrl . $this->endPoint , 'message' => $response]);
            $this->response['status'] = false;
            $this->response['message'] = $response['message'];

        } catch (\Exception $ex) {

            Log::error('Error Gateway ', ['data' => $this->params, 'url' => $this->baseUrl . $this->endPoint , 'message' => $ex->getMessage()]);
            $this->response['status'] = false;
            $this->response['message'] = $ex->getMessage();
        }

        return $this;
    }

}
