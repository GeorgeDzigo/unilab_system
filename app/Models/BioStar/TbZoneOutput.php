<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbZoneOutput extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_ZONE_OUTPUT';

    /**
     * @var array
     */
    protected $fillable = [
        'nZoneIdn',
        'nReaderIOIdn',
        'nOutputFlag'
    ];

}
