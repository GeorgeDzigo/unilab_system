<?php

namespace App\Models;

use App\User;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed person
 * @property mixed item
 * @property mixed action
 * @property mixed user
 * @property mixed item_id
 */
class ItemLog extends BaseModel
{

    use CrudTrait;

    /**
     * @var string
     */
    protected $table = 'item_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'person_id',
        'user_id',
        'action',
        'item_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(BackpackUser::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function personPositions()
    {
        return $this->belongsToMany(PeoplePosition::class, 'items_log_position_persons', 'item_log_id', 'position_person_id');
    }

    /**
     * @return string
     */
    public function getPersonName()
    {
        return $this->person ? $this->person->getFullName() : '';
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->item ? $this->item->name : '';
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->user ? $this->user->name : '';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        if ($this->action == 1) {
            return '<span class="badge badge-danger">გაცემა</span>';
        } else {
            return '<span class="badge badge-success">მიღება</span>';
        }

    }

    /**
     * @return query
     */
    public function scopeGetRemovedItem($query)
    {
        return $query->where('item_logs.action',1);
    }

    /**
     * @return query
     */
    public function scopeIdScope($query, $id)
    {
        return $query->where('item_logs.person_id',$id);
    }

    /**
     * @return string
     */
    public function getItemType()
    {
        return $this->item ? $this->item->type->name : '';
    }

    /**
     * @return query
     */
    public function scopeSearchByDate($query, $from, $till)
    {
        return $query->where('item_logs.created_at', '>=', $from)->where('item_logs.created_at', '<=', date('Y-m-d',strtotime($till) + 86400));
    }

}
