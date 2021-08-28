<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbZoneDoor extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_ZONE_DOOR';

    /**
     * @var array
     */
    protected $fillable = [
        'nZoneDoorIdn',
        'nZoneIdn',
        'nDoorIdn',
        'nDoorDept',
        'nTimezone'
    ];

}
