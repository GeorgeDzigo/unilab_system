<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class UpdateItemsQrCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateItemsQrCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update items qr code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Item::chunk(200, function($items) {
            /**
             * @var $item Item
             */
            foreach($items as $item) {
                $item->generateQr();
            }
        });

    }

}
