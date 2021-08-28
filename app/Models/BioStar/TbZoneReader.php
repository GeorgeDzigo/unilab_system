<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbZoneReader extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_ZONE_READER';

    /**
     * @var array
     */
    protected $fillable = [
        'nZoneIdn',
        'nReaderIdn',
        'nNodeType',
        'nAttribute',
        'nAttribute2'
    ];

}
