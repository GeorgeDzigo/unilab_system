<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed last_name
 * @property mixed first_name
 * @property mixed id
 * @property mixed cover_image
 * @property mixed departments
 * @property mixed activePositions
 * @property mixed additional_info
 * @property mixed frontCardImage
 * @property mixed backCardImage
 * @property mixed birth_date
 * @property mixed fullName
 * @property mixed personal_number
 * @property mixed card_id
 * @property mixed biostar_card_id
 * @property mixed gender
 * @property mixed unilab_email
 * @property mixed personal_email
 */
class Person extends BaseModel
{

    use CrudTrait, SoftDeletes;

    const IMAGES_MAIN_TEMP_PATH = 'public/person/temp/profile';
    const IMAGE_MAIN_PATH = 'public/person/profile';

    const PERSONAL_CARD_IMAGES = 'person/personal_card';
    const FAMILY_PERSONAL_CARD_IMAGES = 'person/family_personal_card';

    const ENABLE_STATUS = 1;
    const DISABLE_STATUS = 0;

    /**
     * @var string
     */
    protected $table = 'people';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'first_name',
        'last_name',
        'personal_number',
        'card_id',
        'biostar_card_id',
        'cover_image',
        'additional_info',
        'gender',
        'birth_date',
        'unilab_email',
        'personal_email'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'fullName',
        'imageSrc',
        'frontCardImage',
        'backCardImage',
        'personalCards',
        'familyContactPersonalCards',
        'formatBirthDate'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at', 'birth_date'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'additional_info'    => 'json'
    ];

    /**
     * @var Person
     */
    private $localPerson;

    /**
     * @var Request
     */
    private $request;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function positions()
    {
        return $this->hasMany(PeoplePosition::class, 'people_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activePositions()
    {
        return $this->hasMany(PeoplePosition::class, 'people_id', 'id')->where('active', 1);
    }

    /**
     * @return string
     */
    public function getFrontCardImageAttribute()
    {

        if (empty($this->additional_info['personal_card']) || empty($this->additional_info['personal_card']['images'])) {
            return '';
        }

        return asset('storage/' . self::PERSONAL_CARD_IMAGES . '/' . $this->additional_info['personal_card']['images']['front']);
    }

    /**
     * @return string
     */
    public function getBackCardImageAttribute()
    {

        if (empty($this->additional_info['personal_card']) || empty($this->additional_info['personal_card']['images'])) {
            return '';
        }

        return asset('storage/' . self::PERSONAL_CARD_IMAGES . '/' . $this->additional_info['personal_card']['images']['back']);
    }

    /**
     * @return array
     */
    public function getPersonalCardsAttribute()
    {
        return [
            'front'     => $this->frontCardImage,
            'back'      => $this->backCardImage
        ];
    }

    /**
     * @return array|string
     */
    public function getFamilyContactPersonalCardsAttribute()
    {

        if (empty($this->additional_info['family_contact']) || empty($this->additional_info['family_contact']['images'])) {
            return '';
        }

        return [
            'front'     => asset('storage/' . self::FAMILY_PERSONAL_CARD_IMAGES . '/' . $this->additional_info['family_contact']['images']['front']),
            'back'      => asset('storage/' . self::FAMILY_PERSONAL_CARD_IMAGES . '/' . $this->additional_info['family_contact']['images']['back'])
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getFormatBirthDateAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->format('Y-m-d') : '';
    }

    /**
     * @return string
     */
    public function getImageSrcAttribute()
    {

        if (!$this->cover_image) {
            return '';
        }

        $str = explode('/', $this->cover_image);

        array_shift($str );

        return asset('storage/' . implode('/', $str));
    }

    /**
     * @return string
     */
    public function getBirthDateColumn()
    {
        return $this->birth_date ? $this->birth_date->format('Y-m-d') : '';
    }

    /**
     * @return string
     */
    public function getMobile()
    {

        $mobiles = [];

        if (empty($this->additional_info['contact'])) {
            return '';
        }

        if (!empty($this->additional_info['contact']['mobile'])) {
            $mobiles[] = $this->additional_info['contact']['mobile'];
        }

        if (!empty($this->additional_info['contact']['mobile2'])) {
            $mobiles[] = $this->additional_info['contact']['mobile2'];
        }

        if (count($mobiles) == 1) {
            return $mobiles[0];
        }

        return $mobiles ? implode('/', $mobiles) : '';
    }

    /**
     * @param integer $id
     * 
     * @return string
     */
    public function scopePersonFullName($query, $id)
    {
        $query = $query ->find($id);
        return $query->first_name . ' ' . $query->last_name;
    }

    /**
     * @param integer $statusCode
     * 
     * @return query
     */
    public function scopeGetByStatus($query, $statusCode)
    {
        return $query->where('status', $statusCode);
    }

}
