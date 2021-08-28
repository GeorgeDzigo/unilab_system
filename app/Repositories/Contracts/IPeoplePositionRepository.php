<?php


namespace App\Repositories\Contracts;


interface IPeoplePositionRepository extends IBaseRepository
{


    /**
     * @param $requestPosition
     * @param $personId
     * @return mixed
     */
    public function saveData($requestPosition, $personId);


    /**
     * @param $peoplePosition
     * @return mixed
     */
    public function setPeoplePosition($peoplePosition);

    /**
     * @return mixed
     */
    public function getPeoplePosition();

    /**
     * @return mixed
     */
    public function checkDateAndChangeActive();

}
