<?php


namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface IPersonRepository extends IBaseRepository
{

    /**
     * @param $person
     * @return mixed
     */
    public function setPerson($person);

    /**
     * @return mixed
     */
    public function getPerson();

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveData(Request $request);

    /**
     * @return mixed
     */
    public function disablePerson();

    /**
     * @return mixed
     */
    public function activePerson();

    /**
     * @return mixed
     */
    public function modifyPersonStatus();

    /**
     * @return mixed
     */
    public function checkPersonPositionStatus();

}
