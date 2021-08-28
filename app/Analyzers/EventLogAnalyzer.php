<?php

namespace App\Analyzers;

use App\Models\Event\EventLog;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class EventLogAnalyzer extends EventLog
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
     * Pageinate collect type.
     * 
     * @param collect
     * @param array
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
     * Get filtered event data.
     * 
     * @param Illuminate\Http\Request
     * @param integer
     * 
     * @return object
     */
    public function getEvent($request, $personId)
    {
        $this->request = $request;
        $event = $this->select(['event_date','event_type','id'])->personFilter($personId);
        $filtereEvent = $this->dateFilter($event);
        return $this->paginate($this->eventData($filtereEvent));
    }

    /**
     * Analyze event data.
     * 
     * @param query
     * @return Illuminate\Support\Collection
     */
    public function eventData($event)
    {
        $event->chunk(100, function($event)
        {
            foreach($event as $index => $row)
            {
                $row->event_type == 1 ? $this->setEnterDate($row, $event, $index) : $this->setExitDate($row);
            }
        });
        return collect($this->event)->sortByDesc(3);
    }

    /**
     * Analyze enter event data.
     * 
     * @param object/array $row
     * @param object/array $event
     * @param integer $index
     */
    public function setEnterDate($row, $event, $index)
    {
        $this->event[$this->eventIteration][] = $row->event_date;        
        $this->nextActionExist($event, $index) ? $this->analyzeNextEnter($event, $index, $row) : $this->setAbnormalEnter($row->id);
    }

    /**
     * Analyze next enter action.
     * 
     * @param object/array $event
     * @param object/array $row
     * @param integer $index
     */
    public function analyzeNextEnter($event, $index, $row)
    {
        if($this->nextActionInCurrentDate($event, $index, $row))
        {        
           $this->setAbnormalEnter($row->id);
        }
        elseif($this->nextActionIsEnter($event, $index))
        {
            $this->setAbnormalEnter($row->id);
        }
    }

    /**
     * Check if next action is in current date.
     * 
     * @param object/array $event
     * @param object/array $row
     * @param integer $index
     * 
     * @return bool
     */
    public function nextActionInCurrentDate($event, $index, $row)
    {
        return $event[$index +1]->event_date->format('Y-m-d') != $row->event_date->format('Y-m-d');
    }

    /**
     * Set abnormal enter data.
     * 
     * @param integer
     */
    public function setAbnormalEnter($id)
    {
        $this->event[$this->eventIteration][] = __("----") ;
        $this->event[$this->eventIteration][] = __("----") ;
        $this->event[$this->eventIteration][] = $id;
        $this->eventIteration ++;
    }

    /**
     * Check if next person action exist.
     * 
     * @param object
     * @param integer
     * 
     * @return bool
     */
    public function nextActionExist($event, $index)
    {
        return isset($event[$index + 1]);
    }

    /**
     * Check if next action is enter/normal.
     * 
     * @param object
     * @param integer
     * 
     * @return bool
     */
    public function nextActionIsEnter($event, $index)
    {
        return $event[$index + 1]->event_type == 1;
    }

    /**
     * Check if first action is enter/normal.
     * 
     * @return bool
     */
    public function isLastRecordSet()
    {
        return isset($this->event[$this->eventIteration][0]);
    }

    /**
     * Analyze exit event data.
     * 
     * @param object/array
     */
    public function setExitDate($row)
    {
        $this->isLastRecordSet() ? $this->setNormalExitDate($row) : $this->setAbnormalExitDate($row);
        $this->eventIteration ++;
    }

    /**
     * Set normal exit data params.
     * 
     * @param object
     */
    public function setNormalExitDate($row)
    {  
        $this->event[$this->eventIteration][] = $row->event_date;
        $this->event[$this->eventIteration][] = $this->dateCompare($this->event[$this->eventIteration][0], $row->event_date);
        $this->event[$this->eventIteration][] = $row->id;
    }

    /**
     * Set default data params if exit data is abnormal.
     * 
     * @param object
     */
    public function setAbnormalExitDate($row)
    {
        $this->event[$this->eventIteration][0] = __("----");
        $this->event[$this->eventIteration][]  = $row->event_date;
        $this->event[$this->eventIteration][]  =  __("----");
        $this->event[$this->eventIteration][] = $row->id;
    }

    /**
     * Compare two data.
     * 
     * @param string/object
     * @param string/object
     * 
     * @return string
     */
    public function dateCompare($seconds, $secondTwo)
    {
        $seconds = strtotime($secondTwo) - strtotime($seconds);
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
     * @param integer
     * @return integer
     */
    protected function timeStampToDays($seconds)
    {
        return intval(intval($seconds) / (3600*24));
    }

    /**
     * @param integer
     * @return integer
     */
    protected function timeStampTohours($seconds)
    {
        return (intval($seconds) / 3600) % 24;
    }

    /**
     * @param integer
     * @return integer
     */
    protected function timeStampTominutes($seconds)
    {
        return (intval($seconds) / 60) % 60;
    }

    /**
     * @param integer
     * @return bool
     */
    protected function isSecondsConverted($seconds)
    {
        return intval($seconds) % 60 > 0;
    }

    /**
     * @param integer
     * @return string
     */
    protected function echoMinute($seconds)
    {
        if ($this->isSecondsConverted($seconds))
        {
            return __("1 წუთი ");
        }
    }

    /**
     * Check if time include in request.
     * 
     * @return bool
     */
    public function isTimeFiled()
    {
        return $this->request->filled(['from','till']);
    }

    /**
     * Filter by default date (last 30 days).
     * 
     * @param object/query
     * @return object/query
     */
    public function setDefaultDate($qvery)
    {
        $from = date('Y-m-d', strtotime("-30 days"));
        $till = date('Y-m-d');
        return $qvery->searchByDate($from, $till);
    }

    /**
     * Filter by request time range.
     * 
     * @param object/query
     * @return object/query
     */
    public function dateFilterAction($qvery)
    {
        $from = $this->request->from;
        $till = $this->request->till;
        return $qvery->searchByDate($from, $till);
    }

    /**
     * Analyze date filter.
     * 
     * @param object/query
     * @return object/query
     */
    public function dateFilter($qvery)
    {
        return $this->isTimeFiled() ? $this->dateFilterAction($qvery) : $this->setDefaultDate($qvery);
    }
}
