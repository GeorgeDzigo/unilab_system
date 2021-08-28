<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use QrCode;

/**
 * @property mixed type
 * @property mixed qr_path
 * @property mixed id
 */
class Item extends BaseModel
{

    use CrudTrait, SoftDeletes;

    const QR_FULL_PATH = 'public/items/qr';
    const QR_BASE_PATH = 'items/qr';

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'name',
        'number',
        'qr_path',
        'attributes',
        'type_id',
        'action'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'attributes'    => 'json'
    ];

    protected $appends = [
        'qrSrc'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ItemType::class, 'type_id', 'id');
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->type ? $this->type->name : '';
    }

    /**
     * @return string
     */
    public function getQrSrc()
    {
        return asset('storage/' . $this->qr_path);
    }

    /**
     * @return string
     */
    public function getQrSrcAttribute()
    {
        return asset('storage/' . $this->qr_path);
    }

    /**
     * Generate qr.
     */
    public function generateQr()
    {

        $resp = QrCode::format('png')
            ->margin(0)
            ->size(50)
            ->generate(json_encode(['id' => $this->id]));

        Storage::put(Item::QR_FULL_PATH  . '/qr_' . $this->id . '.png', $resp);

        $this->update([
            'qr_path'   => Item::QR_BASE_PATH  . '/qr_' . $this->id . '.png'
        ]);

    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        if ($this->status == 1) {
            return 'აქტიური';
        } else {
            return 'არააქტიური';
        }

    }

}
