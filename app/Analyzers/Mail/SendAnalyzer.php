<?php

namespace App\Analyzers\Mail;

use App\Models\Tamplate;
use App\Models\PeoplePosition;
use App\Models\Person;
use Exception;
//email eloq model
use App\Models\Emails;
use App\Models\EmailGroups;
//email send function
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
//email template
use App\Mail\Mail as MailModel;


class SendAnalyzer extends EmailGroups
{

    /**
     * Requets object Instance.
     *
     * @var object
     */
    protected $request;

    /**
     * Template object Instance.
     *
     * @var object
     */
    protected $templateObj;

    /**
     * Email Group Id Instance.
     *
     * @var integer
     */
    protected $groupId;

    /**
     * Send And Record Email Group.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendAndRecord($request)//, $emails, $cliendName
    {
        $this->request = $request;
        if(!$this->getExtraMails() && !$this->getGroupMails())
        {
            return redirect()->route('mail.send')->withErrors([__('იმეილი ვერ მოიძებნა')]);
        }
        $this->defineObjects();
        $this->storeEmailGroup();
        $statusMails = $this->sendMail($this->getExtraMails());
        $statusGroup = $this->sendMail($this->getGroupMails());
        return redirect()->route('mail.dashboard')->with('success', __('Email წარმატებით გაიგზავნა'));
    }

    /**
     * @define objects
     */
    public function defineObjects()
    {
        $this->templateObj = $this->request->filled('template') ? Tamplate::find($this->request->template) : NULL;
    }

    /**
     * Store Email Group.
     */
    public function storeEmailGroup()
    {
        $this->name = $this->request->name;
        $this->user_id = auth()->user()->id;
        $this->body_content = $this->request->body;
        $this->template_id = $this->templateObj ? $this->templateObj->id : NULL;
        $this->save();
        $this->groupId = $this->latest()->first()->id;
    }

    /**
     * Check if Emails Exist.
     *
     * @return array/string
     */
    public function sendMail($emails)
    {
        return $emails ? $this->sendWorker($emails) : '';
    }

    /**
     * Send Email.
     *
     * @define error massage
     */
    public function sendWorker($emails)
    {
        foreach($emails as $email)
        {
            $id = $this->storeEmails($email, 2);
            $personName = is_array($email)? Person::find($email[1])->first_name : NULL;
            $sendableEmail = is_array($email) ? $email[0] : $email;
            try
            {
                // throw new Exception('Division by zero.');
                Mail::to($sendableEmail)
                    ->send(new MailModel($this->request->get('body'), $this->templateObj, $personName,$this->request->get('subject')));
                $this->updateStatus($id, 1);
            }
            catch (Exception $exception)
            {
                Log::info('error_send_email', [
                    'error' => $exception
                ]);
                $this->updateStatus($id, $exception->getMessage());
            }
        }
    }

    /**
     * Store Emails.
     *
     * @return object
     */
    public function storeEmails($email, $status)
    {
        $emailsObj =  new Emails;
        is_array($email) ? $emailsObj->person_id = $email[1] : $emailsObj->extra_email = $email;
        $emailsObj->status = $status;
        $emailsObj->group_id = $this->groupId;
        $emailsObj->save();
        return $emailsObj;
    }

    /**
     * Update Email Status.
     */
    public function updateStatus($id, $status)
    {
        $id->status = $status;
        $id->save();
        $id = NULL;
    }

    /**
     * @return array
     */
    public function getExtraMails()
    {
        return $this->request->filled('emails') ? $this->checkExtraMails($this->request->emails) : Null;
    }

    /**
     * Get Unique Extra Emails.
     *
     * @return array
     */
    public function checkExtraMails($extraMails)
    {
        $extraMails = array_values(array_unique($extraMails));
        return $this->getGroupMails() ? $this->getExtraUniqueMails($extraMails) : $extraMails;
    }

    /**
     * Check Unique Extra Emails.
     *
     * @return array
     */
    public function getExtraUniqueMails($extraMails)
    {
        $filteredMails = [];
        foreach($extraMails as $extraMail)
        {
           foreach($this->getGroupMails() as $groupMail)
            {
                if(!in_array($extraMail, $groupMail))
                {
                    $filteredMails[] = $extraMail;
                    break;
                }
            }
        }
        return $filteredMails;
    }

    /**
     * @return array
     */
    public function getGroupMails($param=NULL)
    {
        return $this->request->filled('group') ? $this->parseGroupEmails($param) : Null;
    }

    /**
     * Convert Users Id To Emails And Emails Id.
     *
     * @param any type ;) $param
     *
     * @return array
     */
    public function parseGroupEmails($param)
    {
        $usersId = $this->getUsersId();
        return Person::getByStatus($this->request->user_status)
            ->find($usersId)
            ->map(function($data, $index) use($param)
            {
                $email = $data->unilab_email ? $data->unilab_email : $data->personal_email;
                return $param ? $data->id : [$email, $data->id];
            })
            ->toArray();
    }

    /**
     * Get Unique Users Id From Groups
     *
     * @return array
     */
    public function getUsersId()
    {
        /**
         * @var array
         */
        $position = [];

        /**
         * @var array
         */
        $departament = [];

        /**
         * @var object PeoplePosition
         */
        $PeoplePosition = new PeoplePosition;

        /**
         * @var string $groupRow
         */
        foreach($this->request->group as $groupRow)
        {
            $groupRow[0]=='%' ? $position[] = substr($groupRow, 1) : $departament[] = $groupRow;
        }

        /**
         * @var array $position
         */
        $position = $PeoplePosition->positionPeople($position);

        /**
         * @var array $departament
         */
        $departament = $PeoplePosition->departamentPeople($departament);

        /**
         * @return array unique ids
         */
        return array_unique(array_merge($position, $departament));
    }

}
