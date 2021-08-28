<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property mixed status
 */
class BaseModel extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    /**
     * @return string
     */
    public function getStatusColumn()
    {

        if ($this->status == 1) {
            return '<span class="badge badge-success">აქტიური</span>';
        } else {
            return '<span class="badge badge-danger">გათიშული</span>';
        }

    }

}
