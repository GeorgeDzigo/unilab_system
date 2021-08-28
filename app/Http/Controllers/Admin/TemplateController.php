<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tamplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MailRequest\TemplateRequest;
use App\Analyzers\Mail\TemplateAnalyzer;

class TemplateController extends Controller
{
    /**
     * @var int
     */
    protected $perPage = 5;

    /**
     * Create a new controller instance.
     */
    public function __construct(TemplateAnalyzer $templateAnalyzer)
    {
        $this->analyzer = $templateAnalyzer;
    }

    /**
     * Show the template dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendor.backpack.base.email.template.dashboard')
            ->with('templateDatas', Tamplate::orderBy('id', 'desc')->paginate($this->perPage));
    }

    /**
     * Show template add form.
     *
     * @return \Illuminate\Http\Response
     */
    public function addForm()
    {
        return view('vendor.backpack.base.email.template.add_template');
    }

    /**
     * Store template and redirect template dashboard.
     *
     * @param object $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateRequest $request)
    {
        return $this->analyzer->store($request);
    }

    /**
     * Delete template and redirect template dashboard.
     *
     * @param object $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return $this->analyzer->distroyRecord($request->id);
    }

    /**
     * Copy template and redirect template dashboard.
     *
     * @param object $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        return $this->analyzer->copyRecord($request->id);
    }

    /**
     * Show the template update form.
     *
     * @param integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateForm($id)
    {
        $template =  Tamplate::find($id);
        return  $template ? view('vendor.backpack.base.email.template.update_template')->with('templateData', $template) :
            redirect()->route('template.list')->with('error', __('მსგავსი Template ვერ მოიძებნა'));
    }

    /**
     * Update template and redirect template dashboard.
     * 
     * @param integer $id
     * @param object App\Analyzers\Mail\TemplateAnalyzer
     * @return \Illuminate\Http\Response
     */
    public function update(TemplateRequest $request, $id)
    {
        return $this->analyzer->updateRecord($request, $id);
    }
}
