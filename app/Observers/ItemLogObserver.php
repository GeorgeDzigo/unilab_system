<?php

namespace App\Observers;

use App\Models\Item;
use App\Models\ItemLog;

class ItemLogObserver
{
    /**
     * Handle the item log "created" event.
     *
     * @param  \App\Models\ItemLog  $itemLog
     * @return void
     */
    public function created(ItemLog $itemLog)
    {
        Item::find($itemLog->item_id)
                ->update([
                    'action'    => $itemLog->action
                ]);
    }

    /**
     * Handle the item log "updated" event.
     *
     * @param  \App\Models\ItemLog  $itemLog
     * @return void
     */
    public function updated(ItemLog $itemLog)
    {
        Item::find($itemLog->item_id)
            ->update([
                'action'    => $itemLog->action
            ]);
    }


}
