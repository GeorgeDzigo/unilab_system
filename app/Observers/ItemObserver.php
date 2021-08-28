<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemObserver
{

    /**
     * Handle the item "created" event.
     *
     * @param  \App\Models\Item  $item
     * @return void
     */
    public function created(Item $item)
    {

        // Generate qr.
        $item->generateQr();

    }


}
