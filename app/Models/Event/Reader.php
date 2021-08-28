<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed name
 */
class Reader extends BaseEventModel
{

    use SoftDeletes;

    const READER_TYPE_ENTRANCE = 1;
    const READER_TYPE_EXIT = 2;

    /**
     * @var string
     */
    protected $table = 'readers';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'biostar_reader_id',
        'type'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

}
