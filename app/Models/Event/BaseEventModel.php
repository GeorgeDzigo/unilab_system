<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BaseEventModel extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

}
