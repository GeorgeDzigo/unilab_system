<?php

namespace App\Models;

use App\Traits\Upload;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed src
 */
class TempImage extends BaseModel
{

    use Upload;

    /**
     * @var string
     */
    protected $table = 'temp_images';

    /**
     * @var array
     */
    protected $fillable = [
        'src', 'type'
    ];

}
