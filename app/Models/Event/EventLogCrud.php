<?php


namespace App\Models\Event;


use App\Models\Person;

/**
 * @property Person person
 * @property Reader reader
 */
class EventLogCrud extends EventLog
{

    /**
     * @return array|string|null
     */
    public function getActionName()
    {
        if ($this->event_type == self::EVENT_TYPE_ENTRANCE) {
            return __('შემოსვლა');
        } else if ($this->event_type == self::EVENT_TYPE_EXIT) {
            return __('გასვლა');
        }

        return '';
    }

    /**
     * @return mixed|string
     */
    public function getPersonName()
    {
        return $this->person ? $this->person->fullName : '';
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->reader ? $this->reader->name : '';
    }

}
