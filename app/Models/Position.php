<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed status
 */
class Position extends BaseModel
{

    use CrudTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'positions';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
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

    /**
     * @return query
     */
    public function scopeActivePosition($query)
    {
        return $query->where('status',1);
    }

}
