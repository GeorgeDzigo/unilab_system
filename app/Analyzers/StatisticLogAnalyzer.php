<?php

namespace App\Analyzers;
use App\Models\ItemLog;
use App\Models\Person;
use App\Models\Event\EventLog;
use App\Analyzers\EventLogAnalyzer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class StatisticLogAnalyzer extends ItemLog
{
    /**
     * @var integer
     */
    protected $perPage = 15;

    /**
     * @var integer
     */
    protected $eventIteration = 0;

    /**
     * @var array
     */
    protected $event = [];

    /**
     * @var array
     */
    protected $eventResponse = [];

    /**
     * @var integer
     */
    protected $eventDifference = 0;

    /**
     * @return array
     */
    public function index($request, $action)
    {
        $this->request = $request;
        return method_exists($this, $action) && is_callable(array($this, $action)) ? call_user_func(array($this, $action)) : $this->teq();
    }

    /** 
     * Get user delay response.
     * 
     * @return array  
     */
    public function delay()
    {
        return [
            'statisticData'=> $this->paginate($this->getDelayData()),
            'action'=> 'Users Delay',
            'columns'=>[__('სახელი/გვარი'), __('მობილური'), __('პირადი ნომერი'), __('უნილაბის მეილი'), __('დაყოვნება')]
        ];
    }

    /**
     * Get user delay data.
     * 
     * @return Illuminate\Support\Collection
     */
    public function getDelayData()
    {
        $persons = $this->dateFilter((new EventLog))->select('person_id')->groupBy('person_id')->get();
        foreach($persons as $index=> $person)
            {
                $this->collectEventData($person->person_id);
                $this->eventDifference != 0 ? $this->eventResponse[] = $this->personEventDelay(Person::find($person->person_id)) : '';
                $this->clearCache();
            }
        return $this->searchFilter(collect($this->eventResponse)->sortByDesc('diff'));
    }

    /**
     * Set renderable data.
     * 
     * @param object
     * @return array
     */
    public function personEventDelay($person)
    {
        return [
                'fullname' => $person->getFullName(),
                'mobile'   => $person->additional_info["contact"]["mobile"],
                'passport' => $person->personal_number,
                'email'    => $person->unilab_email ? : $person->personal_email,
                'diff'     => $this->secondsToTime($this->eventDifference)
            ];
    }

    /**
     * Convert seconds to time(days, hours, minutes).
     * 
     * @param integer
     * @return string
     */
    public function secondsToTime($seconds)
    {
        $ret = "";
        $days = intval(intval($seconds) / (3600*24));
        $hours = (intval($seconds) / 3600) % 24;
        $minutes = (intval($seconds) / 60) % 60;
        $days    > 0 ? $ret .= __("$days დღე ") : '';
        $hours   > 0 ? $ret .= __("$hours საათი ") : '';
        $minutes > 0 ? $ret .= __("$minutes წუთი ") : '';
        return $ret;
    }

    /**
     * Analyze person events.
     * 
     * @param object
     */
    public function collectEventData($id)
    {
        $this->dateFilter((new EventLog))->personFilter($id)->
        chunk(100, function ($flights) {
            foreach($flights as $index => $row)
            {
                if($index == 0)
                {
                    if($row->event_type != 1)
                    {
                        $this->eventIteration ++;
                        continue;
                    }
                }
                $row->event_type == 1 ? $this->setEnterDate($row, $flights, $index) : $this->setExitDate($row);
            }
        });
    }

    /**
     * Clear event global variables.
     */
    public function clearCache()
    {
        $this->eventDifference = 0;
        $this->event = [];
    }

    /**
     * Analyze enter event data.
     * 
     * @param object $row
     * @param object $event
     * @param integer $index
     */
    public function setEnterDate($row, $event, $index)
    {
        if(isset($event[$index + 1]))
        {
            $lastDate = $event[$index + 1]->event_date->format('Y-m-d');
            $currentDate = $row->event_date->format('Y-m-d');
            $isNextActionExit = $event[$index + 1]->event_type == 2;
            $this->checkEnterDate($lastDate, $currentDate, $isNextActionExit) ? $this->event[$this->eventIteration][] = $row->event_date : $this->eventIteration ++;
        }
    }

    /**
     * Check if next action is in current date.
     * 
     * @param bool $lastDate
     * @param bool $currentDate
     * @param bool $isNextActionExit
     * @return bool
     */
    public function checkEnterDate($lastDate, $currentDate, $isNextActionExit)
    {
        return $isNextActionExit && $lastDate == $currentDate;
    }

    /**
     * Analyze exit action and calculate person delay.
     * 
     * @param object
     */
    public function setExitDate($row)
    {
        if(isset($this->event[$this->eventIteration][0]))
        {
            $lastDate = $this->event[$this->eventIteration][0]->format('Y-m-d');
            $currentDate = $row->event_date->format('Y-m-d');
            if($lastDate == $currentDate)
            {
                $this->eventDifference += strtotime($row->event_date) - strtotime($this->event[$this->eventIteration][0]);
                $this->eventIteration ++;
            }
        }
    }

    /**
     * @return object
     */
    public function paginate($items, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $this->perPage), $items->count(), $this->perPage, $page, ['path'=>url($this->request->fullUrl())]);
    }

    /**
     * @return array
     */
    public function persons()
    {  
        return [
            'statisticData'=> $this->paginate($this->getStatisticData('person_id')),
            'action'=> 'Users',
            'columns'=>[__('სახელი/გვარი'),__('მობილური'),__('პირადი ნომერი'),__('უნილაბის მეილი'),__('ტექნისკის გამოყენება')]
        ];
    }

    /**
     * @return array
     */
    public function teq()
    {
        return [
            'statisticData'=> $this->paginate($this->getStatisticData('item_id')),
            'action'=> 'Items',
            'columns'=>[__('ID'),__('სახელი'),__('კატეგორია'),__('სტატუსი'),__('შექმნის თარიღი'),__('გამოყენება')]
        ];
    }

    /**
     * @return query
     */
    public function getStatisticData($option)
    {
        return $this->searchFilter($this
            ->dateFilter($this->getRemovedItem())
            ->select(DB::raw('item_logs.*, count(*) as statistic'))
            ->groupBy($option)
            ->orderByDesc(\DB::raw('count(action)')))
            ->get();
    }

    /**
     * @return bool
     */
    public function isTypeSearchFiled()
    {
        return $this->request->filled('teqType');
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
    public function dateFilter($query)
    {
        $this->isTypeSearchFiled() ? $query = $this->typeSearchAction($query) : $query;
        $this->isTimeFiled()       ? $query = $this->dateFilterAction($query) : $this->setDefaultDate($query);        
        return $query;
    } 
    
    /**
     * @return query
     */
    public function typeSearchAction($query)
    {
        $teqType = $this->request->teqType;
        return $query ->whereHas('item',function($query) use($teqType){
            $query->whereHas('type',function($query)use($teqType){
                $query->where('name',$teqType);
            });
        });
    }

    /**
     * @return query
     */
    public function dateFilterAction($query)
    {
        $from = $this->request->from; 
        $till = $this->request->till; 
        Cache::put(['from'=> $from, 'till'=> $till] , 15);
        return $query->searchByDate(Cache::get('from'), Cache::get('till'));
    }

    /**
     * @return query
     */
    public function setDefaultDate($query){
        $from = date('Y-m-d', strtotime("-30 days"));
        $till = date('Y-m-d');
        return $this->isCacheFiled() ? $query->searchByDate(Cache::get('from'),Cache::get('till')) : $query->searchByDate($from, $till);
         
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
    public function isSearchFiled()
    {
        return $this->request->filled('search');
    }
    
    /**
     * @return query
     */
    public function searchFilter($query)
    {
        return $this->isSearchFiled() ? $this->searchFilterAction($query) : $query;
    }

    /**
     * @return query
     */
    public function searchFilterAction($query)
    {
        return $query->take($this->request->search);
    }

}
