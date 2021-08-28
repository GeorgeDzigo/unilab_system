<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends BaseModel
{

    use CrudTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'item_types';

    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @return array
     */
    public static function getAllKeyValue()
    {
        $keyValues = [];

        foreach (self::all() as $data) {
            $keyValues[$data->id] = $data->name;
        }

        return $keyValues;
    }

}
