<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TaUser extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_TA_USER';

    /**
     * @var array
     */
    protected $fillable = [
        'nUserTAIdn',
        'nUserIdn',
        'nType',
        'nTAIdn'
    ];

}
