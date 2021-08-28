<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class BaseBioStarModel extends Model
{

    /**
     * @var string
     */
    protected $connection = 'sqlsrv';

    /**
     * @var bool
     */
    public $timestamps = false;

}
