<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed nEventLogIdn
 * @property mixed nDateTime
 * @property mixed nUserID
 * @property mixed nReaderIdn
 */
class TbEventLog extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_EVENT_LOG';

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
