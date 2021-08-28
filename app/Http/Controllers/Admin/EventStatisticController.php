<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Analyzers\EventLogAnalyzer;

class EventStatisticController extends Controller
{
    public function __construct(EventLogAnalyzer $analyzer, Request $request)
    {
        $this->analyzer= $analyzer;
        $this->request = $request;
    }

    /**
     * Show the event statistic.
     *
     * @return \Illuminate\Http\Response
     */
    public function logStatistic($id)
    {
        return view('vendor.backpack.base.show-event-statistic')
            ->with('events', $this->analyzer->getEvent($this->request, $id))
            ->with('personId', $id);
    }
}
