<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

class Emails extends Model
{
    /**
     * @var string
     */
    protected $table = 'emails';

    /**
     * @return string
     */
    public function getReciver()
    {
        return $this->person_id ? Person::find($this->person_id)->getFullNameAttribute() : $this->extra_email;
    }

    /**
     * 
     * @param integer $id
     * 
     * @return query
     */
    public function scopeGetByGroup($query, $id)
    {
        $query->where('group_id', $id);
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        if ($this->status == 1) 
        {
            return __('გაგზავნილი');
        } 
        elseif($this->status == 2)
        {
            return __('იგზავნება');
        }
        else 
        {
            return __('შეცდომა');
        }
    }
}
