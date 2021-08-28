<?php


namespace App\Repositories\Eloquent;


use App\Models\PeoplePosition;
use App\Models\Person;
use App\Models\TempImage;
use App\Repositories\Contracts\IPeoplePositionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PeoplePositionRepository extends BaseRepository implements IPeoplePositionRepository
{

    /**
     * @var PeoplePosition
     */
    protected $localPeoplePosition;

    /**
     * PeoplePositionRepository constructor.
     * @param PeoplePosition $model
     */
    public function __construct
    (
        PeoplePosition $model
    )
    {
        parent::__construct($model);
    }

    /**
     * @param $peoplePosition
     */
    public function setPeoplePosition($peoplePosition)
    {
        $this->localPeoplePosition = $peoplePosition;
    }

    /**
     * @return PeoplePosition
     */
    public function getPeoplePosition()
    {
        return $this->localPeoplePosition;
    }

    /**
     * @param $requestPosition
     * @param $personId
     * @throws \Exception
     */
    public function saveData($requestPosition, $personId)
    {

        /**
         * Delete if empty person positions array
         */
        if (empty($requestPosition)) {
            $this->model->where('people_id', $personId)->delete();
            return;
        }

        /**
         * Delete old people positions.
         */
        $this->deleteOldPeoplePosition($requestPosition, $personId);

        foreach ($requestPosition as $positionPerson) {

            $this->savePersonPosition($positionPerson, $personId);

        }

    }

    /**
     * @param $positionPerson
     * @param $personId
     */
    private function savePersonPosition($positionPerson, $personId)
    {

        if (array_key_exists('id', $positionPerson)) {
            $peoplePositionModel = self::find($positionPerson['id']);
        } else {
            $peoplePositionModel = null;
        }

        /**
         * Save base data.
         */
        $saveData = [
            'people_id'     => $personId,
            'position_id'   => array_key_exists('position', $positionPerson) ? $positionPerson['position'] : '',
            'department_id' => array_key_exists('department', $positionPerson) ? $positionPerson['department'] : '',
            'doc_number'    => array_key_exists('document_number', $positionPerson) ? $positionPerson['document_number'] : '',
        ];

        if ($positionPerson['date_type'] == 1) {

            $startDate = Carbon::parse($positionPerson['start_to'][0]);
            $endDate = Carbon::parse($positionPerson['start_to'][1]);

            if ( now()->diffInMinutes($endDate, false) < 0 ) {
                $active = 0;
            } else {
                $active = 1;
            }

            // Set some variables.
            $saveData['start_date'] = $startDate;
            $saveData['end_date'] = $endDate;
            $saveData['active'] = $active;

        } else if ($positionPerson['date_type'] == 2){

            if (!array_key_exists('start', $positionPerson)) {
                $startDate = now();
            } else {
                $startDate = Carbon::parse($positionPerson['start']);
            }

            if ( $startDate->diffInMinutes(now(), false) < 0 ) {
                $active = 0;
            } else {
                $active = 1;
            }

            // Set some variables.
            $saveData['start_date'] = $startDate;
            $saveData['end_date'] = null;
            $saveData['active'] = $active;

        }

        if (is_null($peoplePositionModel)) {
            $this->create($saveData);
        } else {
            $peoplePositionModel->update($saveData);
        }

    }

    /**
     * @param $requestPosition
     * @param $personId
     * @throws \Exception
     */
    public function deleteOldPeoplePosition($requestPosition, $personId)
    {

        $data = collect($requestPosition)->where('id', '!=', null);

        /**
         * @var $itm PeoplePosition
         */
        foreach($this->model->where('people_id', $personId)->get() as $itm ){

            if ( is_null( $data->where('id', $itm->id)->first()) ) {
                $itm->delete();
            }

        }

    }

    /*
     * Active people position.
     */
    public function activePeoplePosition()
    {
        $this->localPeoplePosition->update(['active' => 1]);
    }

    /**
     * Disable people position.
     */
    public function disablePeoplePosition()
    {
        $this->localPeoplePosition->update(['active' => 0]);
    }

    /**
     * Check end date and change active people position.
     */
    public function checkDateAndChangeActive()
    {

        /**
         * @var $endDate Carbon
         */
        $endDate = $this->localPeoplePosition->end_date;

        /**
         * If time has expired, disable person position.
         */
        if ( $endDate && now()->diffInMinutes($endDate, false) < 0 ) {

            $this->disablePeoplePosition();

        } else {
            $this->activePeoplePosition();
        }

    }

}
