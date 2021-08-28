<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class EmailGroups extends Model
{
    /**
     * @var string
     */
    protected $table = 'email_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function emailCount()
    {
        return $this->belongsTo(Emails::class, 'id', 'group_id');
    }
}
