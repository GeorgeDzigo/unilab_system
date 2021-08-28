<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbUserCard extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_USER_CARD';

    /**
     * @var array
     */
    protected $fillable = [
        'nCardIdn',
        'nUserIdn',
        'nType',
        'sCardNo',
        'sCustomNo',
        'nSecurityLevel',
        'nDateTime',
        'nExpiryDateTime',
        'nBypass',
        'nStatus',
        'nLatest',
        'nEncryption',
        'nV2CardType'
    ];

}
