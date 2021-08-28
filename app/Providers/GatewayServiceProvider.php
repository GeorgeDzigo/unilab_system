<?php


namespace App\Providers;


use App\Gateway\CardScanner\CardScannerGateway;
use App\Gateway\CardScanner\ICardScannerGateway;
use App\Repositories\Contracts\IBaseRepository;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider  extends ServiceProvider   
{

    /**
     * Bind gateways.
     */
    public function register()
    {
        $this->app->bind(ICardScannerGateway::class, CardScannerGateway::class);
    }

    
}
