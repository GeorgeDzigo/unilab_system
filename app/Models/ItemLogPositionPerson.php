<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemLogPositionPerson extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'items_log_position_persons';

    /**
     * @var array
     */
    protected $fillable = [
        'position_person_id',
        'item_log_id'
    ];
}
