<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

/**
 * @property mixed status
 */
class Department extends BaseModel
{

    use CrudTrait, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'departments';

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
    public function scopeActiveDepartametns($query)
    {
        return $query->where('status',1);
    }


     /*
  |--------------------------------------------------------------------------
  | RELATIONS
  |--------------------------------------------------------------------------
  */

    public function competition()
    {
        return $this->belongsToMany(Competition::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }



}
