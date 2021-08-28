<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbZoneInput extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_ZONE_INPUT';

    /**
     * @var array
     */
    protected $fillable = [
        'nZoneIdn',
        'nReaderIOIdn',
        'sName',
        'nInputFlag'
    ];



}
