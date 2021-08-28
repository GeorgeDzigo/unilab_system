<?php

namespace App\Http\Controllers;

use App\Gateway\CardScanner\ICardScannerGateway;
use App\Gateway\ReaderGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use QrCode;

class TestController extends Controller
{

    public function test(ICardScannerGateway $cardScannerGateway){

        /**
         *
         */
        $cardScannerGateway->makeScan();

        /**
         * @var $responseScan array
         */
        $responseScan = $cardScannerGateway->getResponse();

        dd($responseScan);

        $resp = QrCode::format('png')->margin(0)->size(50)->generate(json_encode(['id' => '1']));

        $res = Storage::put('public/test.png', $resp);

        dd('done');

//        return view('test', ['qr' => $qr]);
    }

}
