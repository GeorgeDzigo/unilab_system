<?php

namespace App\Analyzers;

use App\Models\ItemLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ItemLogAnalyzer extends ItemLog
{    
    /**
     * @var int
     */
    protected $cacheTime = 15;

    /**
     * @var int
     */
    protected $perPage = 15;

    /**
     * @return object
     */
    public function index($id,$request)
    {
        $this->request = $request;
        return $this->paginate($this->getHistoryData($id));
    }

    /**
     * This cell is for paginate
     * 
     * @return object
     */
    public function paginate($items, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $this->perPage), $items->count(), $this->perPage, $page, ['path'=>url($this->request->fullUrl())]);
    }

    /**
     * Collect Main Data
     * 
     * @return query
     */
    public function getHistoryData($id)
    {
        $historyData = $this 
         -> leftjoin('item_logs as main',function($query){
                    $query -> on('main.person_id', 'item_logs.person_id')
                           -> on('main.item_id','item_logs.item_id')
                           -> on('main.id', DB::raw('(select second.id from item_logs as second where second.action = 2 and second.id > item_logs.id limit 1)'));                    
                })
        -> idScope($id)
        -> orderBy('item_logs.created_at', 'desc')
        -> getRemovedItem();
           
        return $this->filter($historyData);
    }
    
    /**
     * @return query
     */
    public function searchedItem($qvery)
    {
        $search = $this->request->search;
        return $qvery ->whereHas('item',function($qvery) use($search)
        {
            $qvery->where('name','like','%'.$this->request->search.'%')
            ->orWhereHas('type',function($qvery)use($search)
            {
                $qvery->where('name','like',$search.'%');
            })
            ->orWhere('item_logs.created_at','like','%'.$search.'%');;
        });
    }
    
    /**
     * @return array
     */
    public function collectHistoryData($Records)
    {
        $historyDatas = [];
        foreach($Records as $Record)
        {
            $historyDatas[] = [
                'date'        => $this->isActive($Record),
                'time'        => $this->dateCompare($Record),
                'name'        => $Record->getItemName(),
                'item_id'     => $Record->item_id,
                'exporter'    => $Record->getUserName(),
                'create_date' => $Record->created_at,
                'categorical' => $Record->getItemType(),            
            ];
        }
        return $historyDatas;
    }
    
    /**
     * @return query
     */
    public function filter($qvery)
    {
        $this->isShowActiveItem() ? $qvery = $this->getActiveItems($qvery) : '';
        $this->isShowPasiveItem() ? $qvery = $this->getReturedItems($qvery): '';
        $this->isSearchFiled()    ? $qvery = $this->searchedItem($qvery): '';
        $this->isTimeFiled()      ? $qvery = $this->timeFiltre($qvery) : $this->setDefaultTime($qvery);
        $historyData = $qvery -> get([
            'item_logs.user_id',
            'item_logs.item_id',
            'item_logs.created_at as created_at',
            'main.created_at as returned_at',
        ]);
        
        return $this->collectHistoryData($historyData);
    }

    /**
     * @return string
     */
    protected function dateCompare($record)
    {
        $record['returned_at'] ? $seconds=$this->returnedTime($record) : $seconds=$this->activeTime($record);
        $ret     = "";
        $days    = $this->timeStampToDays($seconds);
        $hours   = $this->timeStampTohours($seconds);
        $minutes = $this->timeStampTominutes($seconds);
        $days    > 0 ? $ret .= __("$days დღე ")     : '';
        $hours   > 0 ? $ret .= __("$hours საათი ")   : '';        
        $minutes > 0 ? $ret .= __("$minutes წუთი ") : $ret .= $this->echoMinute($seconds); 
        return $ret;
    }

    /**
     * @return query
     */
    public function timeFiltre($query)
    {
        $from = $this->request->from;
        $till = $this->request->till;
        Cache ::put(['from'=> $from, 'till'=> $till] , $this->cacheTime);
        return $query->SearchByDate($from, $till);
    }

    /**
     * @return query
     */
    public function setDefaultTime($query)
    {
        $from = date('Y-m-d', strtotime("-30 days"));
        $till = date('Y-m-d');
        return $this->isCacheFiled() ?  $query->searchByDate(Cache::get('from'),Cache::get('till')) : $query->searchByDate($from, $till);
    }

    /**
     * @return bool
     */
    public function isCacheFiled()
    {
        return Cache::Has('from');
    }

    /**
     * @return bool
     */
    public function isShowActiveItem()
    {
        return $this->request->act == 'active';
    }

    /**
     * @return bool
     */
    public function isShowPasiveItem()
    {
        return $this->request->act == 'pasive';
    }

    /**
     * @return bool
     */
    public function isTimeFiled()
    {
        return $this->request->filled(['from','till']);
    }

    /**
     * @return query
     */
    public function getActiveItems($qvery)
    {
        return $qvery->whereNull('main.created_at');
    }

    /**
     * @return query
     */
    public function getReturedItems($qvery)
    {
        return $qvery->whereNotNull('main.created_at');
    }

    /**
     * @return bool
     */
    public function isSearchFiled()
    {
        return $this->request->filled('search');
    }

    /**
     * @return string
     */
    public function isActive($record)
    {
        return $record['returned_at'] ? $record['returned_at'] : __('აქტიური');
    }

    /**
     * @return int
     */
    public function returnedTime($record)
    {
        return strtotime($record['returned_at']) - strtotime($record['created_at']);
    }

    /**
     * @return int
     */
    public function activeTime($record)
    {
        return time() - strtotime($record['created_at']);
    }

    /**
     * @return int
     */
    public function timeStampToDays($seconds)
    {
        return intval(intval($seconds) / (3600*24));
    }

    /**
     * @return int
     */
    public function timeStampTohours($seconds)
    {
        return (intval($seconds) / 3600) % 24;
    }

    /**
     * @return int
     */
    public function timeStampTominutes($seconds)
    {
        return (intval($seconds) / 60) % 60;
    }

    /**
     * @return int
     */
    public function isSecondsConverted($seconds)
    {
        return intval($seconds) % 60 > 0;
    }

    /**
     * @return string
     */
    public function echoMinute($seconds)
    {
        if ($this->isSecondsConverted($seconds)) {
            return __("1 წუთი "); 
        }
    }
}
