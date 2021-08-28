<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MailRequest\EmailSendRequest;

use App\Models\Tamplate;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmailGroups;
use App\Models\Emails;


use App\Analyzers\Mail\SendAnalyzer;

class EmailController extends Controller
{
    /**
     * @var integer
     */
    protected $dashboardPerPage = 10;

    /**
     * @var integer
     */
    protected $listPerPage = 10;

    /**
     * Show the email groups dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('vendor.backpack.base.email.mail.dashboard')
            ->with('emailGroups', EmailGroups::orderBy('id', 'desc')->paginate($this->dashboardPerPage));
    }

    /**
     * Show the email group send form.
     *
     * @return \Illuminate\Http\Response
     */
    public function send()
    {
        return view('vendor.backpack.base.email.mail.send')
            ->with('tempaltes', Tamplate::get(['name','id']))
            ->with('departaments', Department::activeDepartametns()->get())
            ->with('positions', Position::activePosition()->get());
    }

    /**
     * Store Email Group.
     *
     * @param object $request
     *
     * @param object $analyzer
     *
     * @return \Illuminate\Http\Response
     */
    public function store(EmailSendRequest $request, SendAnalyzer $analyzer)
    {
        return $analyzer->sendAndRecord($request);
    }

    /**
     * Show the email group email list.
     *
     * @param integer $id
     *
     * @return \Illuminate\Http\Response
     */
    public function list($id)
    {
        return view('vendor.backpack.base.email.mail.list')
            ->with('emails', Emails::getByGroup($id)->paginate($this->listPerPage));
    }
}
