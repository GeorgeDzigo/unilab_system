<?php


namespace App\Services\Events\Objects;


use App\Models\BioStar\TbUserCard;
use App\Models\Person;
use App\Services\Events\Contracts\IChangeBiostarUserStatus;

/**
 * @property Person person
 * @property mixed status
 */
class ChangeBiostarUserStatus implements IChangeBiostarUserStatus
{

    /**
     * @var Person
     */
    protected $person;

    /**
     * @var
     */
    protected $status;

    /**
     * @param Person $person
     * @return $this
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return $this|mixed
     */
    public function changeStatus()
    {
        if ($this->status == Person::DISABLE_STATUS) {
            $this->disablePerson();
        }

        return $this;
    }

    /**
     *
     */
    private function disablePerson()
    {
        TbUserCard::where('sCardNo', $this->person->biostar_card_id)->where('nLatest',1)->update([
            'nLatest'   => 0,
            'nStatus'   => 2
        ]);
    }

}
