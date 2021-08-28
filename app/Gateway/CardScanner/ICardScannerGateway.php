<?php

namespace App\Gateway\CardScanner;

use App\Gateway\IBaseGateway;

interface ICardScannerGateway extends IBaseGateway
{

    /**
     * @return mixed
     */
    public function makeScan();

}
