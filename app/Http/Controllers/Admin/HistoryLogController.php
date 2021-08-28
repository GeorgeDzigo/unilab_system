<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Models\ItemLog;
use App\Analyzers\ItemLogAnalyzer;
use Illuminate\Http\Request;

class HistoryLogController extends BaseController
{                   
    public function __construct(ItemLogAnalyzer $datasFromItem, Request $request)
    {
        $this->historyData = $datasFromItem;
        $this->request = $request;
    }

    /**
     * Show the teq statistic.
     *
     * @return \Illuminate\Http\Response
     */
    public function history($id)
    {
        return view('vendor/backpack/base/show-history-log')
            ->with('personId', $id)
            ->with('datas', $this->historyData->index($id, $this->request));
    }

}
