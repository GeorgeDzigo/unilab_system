<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbEventLogBk extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_EVENT_LOG_BK';

    /**
     * @var array
     */
    protected $fillable = [
        'nEventLogIdn',
        'nDateTime',
        'nReaderIdn',
        'nEventIdn',
        'nUserID',
        'nIsLog',
        'nTNAEvent',
        'nIsUseTA',
        'nType'
    ];

}
