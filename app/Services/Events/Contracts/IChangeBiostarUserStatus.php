<?php


namespace App\Services\Events\Contracts;


use App\Models\Person;

interface IChangeBiostarUserStatus
{

    /**
     * @param Person $person
     * @return mixed
     */
    public function setPerson(Person $person);

    /**
     * @param $status
     * @return mixed
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function changeStatus();

}
