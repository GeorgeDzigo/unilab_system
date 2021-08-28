<?php


namespace App\Repositories\Eloquent;


use App\Events\Person\DisablePersonEvent;
use App\Models\PeoplePosition;
use App\Models\Person;
use App\Models\Position;
use App\Models\TempImage;
use App\Repositories\Contracts\IPeoplePositionRepository;
use App\Repositories\Contracts\IPersonRepository;
use App\Repositories\Contracts\ITempImageRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @property IPeoplePositionRepository peoplePositionRepository
 * @property ITempImageRepository tempImageRepository
 */
class PersonRepository extends BaseRepository implements IPersonRepository
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Person
     */
    protected $localPerson;

    /**
     * @var ITempImageRepository
     */
    protected $tempImageRepository;

    /**
     * @var IPeoplePositionRepository
     */
    protected $peoplePositionRepository;

    /**
     * PersonRepository constructor.
     * @param Person $model
     * @param ITempImageRepository $tempImageRepository
     * @param IPeoplePositionRepository $peoplePositionRepository
     */
    public function __construct
    (
        Person $model,
        ITempImageRepository $tempImageRepository,
        IPeoplePositionRepository $peoplePositionRepository
    )
    {
        parent::__construct($model);
        $this->tempImageRepository = $tempImageRepository;
        $this->peoplePositionRepository = $peoplePositionRepository;
    }

    /**
     * @param $person
     */
    public function setPerson($person)
    {
        $this->localPerson = $person;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->localPerson;
    }

    /**
     * @param Request $request
     * @return $this
     * @throws \Exception
     */
    public function saveData(Request $request)
    {

        try {
            DB::beginTransaction();

            /**
             * Set request.
             */
            $this->request = $request;

            /**
             * @var $saveData array
             */
            $saveData = $request->only(
                [
                    'first_name',
                    'last_name',
                    'card_id',
                    'personal_number',
                    'status',
                    'gender',
                    'birth_date',
                    'unilab_email',
                    'personal_email',
                    'biostar_card_id'
                ]);

            $saveData['status'] = 1;

            if (array_key_exists('birth_date', $saveData)) {
                $saveData['birth_date'] = Carbon::parse($saveData['birth_date']);
            }

            if ($request->get('id')) {

                $this->localPerson = $this->find($request->get('id'));

                if (is_null($this->localPerson)) {
                    throw new \Exception('Person not found', 404);
                }

                $this->localPerson->update($saveData);

            } else {

                // Save exhibition.
                $this->localPerson = $this->create($saveData);

            }

            /**
             * Save positions.
             */
            $this->peoplePositionRepository->saveData($request->get('positions'), $this->localPerson->id);

//            /**
//             * Save images.
//             */
//            $this->saveImages();

            /**
             * Save personal card images.
             */
            $additionalInfo =  $this->savePersonalCardImages();

            /**
             * Save family card image.
             */
            $additionalInfo = $this->saveFamilyCardImages($additionalInfo);

            $this->localPerson->update(['additional_info' => $additionalInfo]);

            DB::commit();

        } catch (\Exception $ex){
            DB::rollBack();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }


        return $this;
    }

    /**
     * Modify additional info.
     * @param $additionalInfo
     * @return
     */
    public function saveFamilyCardImages($additionalInfo)
    {

        if (empty($additionalInfo['family_contact']) || empty($additionalInfo['family_contact']['images'])) {
            return $additionalInfo;
        }

        $images = $additionalInfo['family_contact']['images'];

        /**
         * Save front image.
         */
        if (preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $images['front'])){

            $tempImagePath = $this->saveBase64Data($images['front']);
            $img = \Image::make($tempImagePath);

            $imgName = Person::FAMILY_PERSONAL_CARD_IMAGES . '/' . $this->localPerson->id . '/front.png';

            Storage::disk('public')->put($imgName,  (string)$img->encode());

            $additionalInfo['family_contact']['images']['front'] = $this->localPerson->id . '/front.png';
        } else if (is_string($images['front'])){
            $additionalInfo['family_contact']['images']['front'] = $this->localPerson->id . '/front.png';
        }

        /**
         * Save back image.
         */
        if (preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $images['back'])){

            $tempImagePath = $this->saveBase64Data($images['back']);
            $img = \Image::make($tempImagePath);

            $imgName = Person::FAMILY_PERSONAL_CARD_IMAGES . '/' . $this->localPerson->id . '/back.png';

            Storage::disk('public')->put($imgName, (string)$img->encode());

            $additionalInfo['family_contact']['images']['back'] = $this->localPerson->id . '/back.png';
        } else if ( is_string($images['back']) ) {
            $additionalInfo['family_contact']['images']['back'] = $this->localPerson->id . '/back.png';
        }

        return $additionalInfo;
    }

    /**
     * Modify additional info.
     */
    public function savePersonalCardImages()
    {

        /**
         * @var $additionalInfo array
         */
        $additionalInfo = $this->request->get('additional_info');


        if (empty($additionalInfo['personal_card']) || empty($additionalInfo['personal_card']['images'])) {
            return $additionalInfo;
        }

        $images = $additionalInfo['personal_card']['images'];

        /**
         * Save front image.
         */
        if (preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $images['front'])){

            $tempImagePath = $this->saveBase64Data($images['front']);
            $img = \Image::make($tempImagePath);

            $imgName = Person::PERSONAL_CARD_IMAGES . '/' . $this->localPerson->id . '/front.png';


            Storage::disk('public')->put($imgName,  (string)$img->encode());

            $additionalInfo['personal_card']['images']['front'] = $this->localPerson->id . '/front.png';
        } else if (is_string($images['front'])){
            $additionalInfo['personal_card']['images']['front'] = $this->localPerson->id . '/front.png';
        }

        /**
         * Save back image.
         */
        if (preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $images['back'])){

            $tempImagePath = $this->saveBase64Data($images['back']);
            $img = \Image::make($tempImagePath);

            $imgName = Person::PERSONAL_CARD_IMAGES . '/' . $this->localPerson->id . '/back.png';

            Storage::disk('public')->put($imgName, (string)$img->encode());

            $additionalInfo['personal_card']['images']['back'] = $this->localPerson->id . '/back.png';
        } else if ( is_string($images['back']) ) {
            $additionalInfo['personal_card']['images']['back'] = $this->localPerson->id . '/back.png';
        }

        return $additionalInfo;
    }

    /**
     * @param $img
     * @return string
     */
    public function saveBase64Data($img)
    {
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = public_path('/temp/') . 'temp.jpeg';
        file_put_contents($file, $data);

        return 'temp/temp.jpeg';
    }

    /**
     * @throws \Exception
     */
    private function deleteImage()
    {
        if ($this->localPerson->cover_image) {
            try {
                if ( Storage::exists($this->localPerson->cover_image) ) {
                    Storage::delete($this->localPerson->cover_image);
                }
            }catch (\Exception $ex) {
                logger($ex->getMessage());
            }
        }
    }

    /**
     * Save images.
     */
    private function saveImages()
    {

        if ( $this->request->get('tempImageId') ) {

            // Delete if exist file.
            $this->deleteImage();

            /**
             * @var $tempImage TempImage
             */
            $tempImage =  $this->tempImageRepository->find($this->request->get('tempImageId'));

            $explodeImage =  explode('/', $tempImage->src);

            $src = Person::IMAGE_MAIN_PATH . '/' . $this->localPerson->id  . '/'.    $explodeImage[count($explodeImage) - 1];

            // Save image in storage.
            Storage::put($src, (string)Storage::get($tempImage->src));

            // Save image src in db.
            $this->localPerson->update(['cover_image' => $src]);

        }

    }

    /**
     * @return bool|mixed
     */
    public function checkPersonPositionStatus()
    {

        $activeStatusCount = 0;

        /**
         * @var $peoplePosition PeoplePosition
         */
        foreach($this->localPerson->activePositions as $peoplePosition) {

            /**
             * @var $position Position
             */
            $position = $peoplePosition->position;

            if ($position->status == 1) {
                $activeStatusCount++;
            }

        }

        return $activeStatusCount > 0;
    }

    /**
     * Modify person status.
     */
    public function modifyPersonStatus()
    {

        if ($this->localPerson->positions->count() && !$this->localPerson->activePositions->count()) {
            $this->disablePerson();
        } else {
            $this->activePerson();
        }

    }

    /**
     *Disable Person
     */
    public function disablePerson()
    {
        $oldStatus = $this->localPerson;
        $this->localPerson->update(['status' => Person::DISABLE_STATUS]);
        event(new DisablePersonEvent($this->localPerson, $oldStatus));
    }

    /**
     * Active Person
     */
    public function activePerson()
    {
        $this->localPerson->update(['status' => Person::ENABLE_STATUS]);
    }

}
