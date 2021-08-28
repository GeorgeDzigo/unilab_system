<?php

namespace App\Gateway\CardScanner;

use App\Gateway\BaseGateway;

class CardScannerGateway extends BaseGateway implements ICardScannerGateway
{


    /**
     * @return void
     */
    protected function setBaseCredentials()
    {
        $this->baseUrl = config('services.card_scanner.base_url');
    }

    /**
     * Make scan.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeScan()
    {

        $this->endPoint = '/';
        $this->method = 'GET';
        $this->requestOption = 'query';

        // Do request
        $this->doRequest();

    }

}
