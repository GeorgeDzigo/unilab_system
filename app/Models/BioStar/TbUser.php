<?php

namespace App\Models\BioStar;

use Illuminate\Database\Eloquent\Model;

class TbUser extends BaseBioStarModel
{

    /**
     * @var string
     */
    protected $table = 'TB_USER';

    /**
     * @var array
     */
    protected $fillable = [
        'nUserIdn',
        'sUserName',
        'nDepartmentIdn',
        'sTelNumber',
        'sEmail',
        'sUserID',
        'nStartDate',
        'nEndDate',
        'nAdminLevel',
        'nAuthMode',
        'nAuthLimitCount',
        'nTimedAPB',
        'nEncryption'
    ];

}
