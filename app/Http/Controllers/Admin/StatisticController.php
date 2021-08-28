<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemLog;
use App\Analyzers\StatisticLogAnalyzer;


class StatisticController extends Controller
{
    public function __construct(Request $request, StatisticLogAnalyzer $StatisticLogAnalyzer)
    {
        $this->request = $request;
        $this->statisticAnalyzer = $StatisticLogAnalyzer;
    }

    /**
     * Show the statistic dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPerson($action)
    {
        return view('vendor/backpack/base/statistic',$this->statisticAnalyzer->index($this->request,$action));
    }

}
