<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbTaLogHistory extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_TA_LOG_HISTORY';

    /**
     * @var array 
     */
    protected $fillable = [
        'nHistroryIdn',
        'nUpdateTime',
        'sUserID',
        'sIP',
        'nDateTime',
        'nUserIdn',
        'nReaderIdn',
        'nTNAEvent',
        'nFirstInTime',
        'nLastOutTime',
        'sFirstInEvent',
        'sLastOutEvent',
        'nTAResult',
        'nWorkTime',
        'nTimeCategoryIdn',
        'nFlag'
    ];

}
