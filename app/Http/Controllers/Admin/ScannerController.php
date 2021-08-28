<?php

namespace App\Http\Controllers\Admin;

use App\Gateway\CardScanner\ICardScannerGateway;
use App\Http\Controllers\Controller;
use App\Utilities\ServiceResponse;
use Illuminate\Http\Request;

/**
 * @property ICardScannerGateway cardScannerGateway
 */
class ScannerController extends Controller
{

    /**
     * @var
     */
    protected $data;

    /**
     * @var ICardScannerGateway
     */
    protected $cardScannerGateway;

    /**
     * ScannerController constructor.
     * @param ICardScannerGateway $cardScannerGateway
     */
    public function __construct
    (
        ICardScannerGateway $cardScannerGateway
    )
    {
        $this->cardScannerGateway = $cardScannerGateway;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scan(Request $request)
    {

        try {

            /**
             * Make scan.
             */
            $this->cardScannerGateway->makeScan();

            /**
             * @var $responseScan array
             */
            $responseScan = $this->cardScannerGateway->getResponse();

            /**
             * If response status is false, throw exception and show client.
             */
            if (!$responseScan['status']) {
                throw new \Exception($responseScan['message'], 500);
            }

            $this->data['cardId'] = $responseScan['data'];

//            $this->data['cardId'] = uniqid() . '-სატესტო';

        } catch (\Exception $ex) {
            return ServiceResponse::jsonNotification($ex->getMessage(), $ex->getCode(), []);
        }

        return ServiceResponse::jsonNotification(__('ბარათი წარმატებით დასკანერდა'), 200,  $this->data );
    }

}
