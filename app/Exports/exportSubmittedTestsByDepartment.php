<?php

namespace App\Exports;

use App\Models\SubmittedTest;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class exportSubmittedTestsByDepartment implements FromView
{
    public function __construct(int $departmanent_id, int $competition_id) {
        $this->dep_id = $departmanent_id;
        $this->comp_id = $competition_id;
    }
    public function view(): View
    {
        return view('excelExport.submitted_test_export', [
            'submittedTests' => SubmittedTest::where('department_id', $this->dep_id)->where('competition_id', $this->comp_id)->where('selection_status', 1)->get(),
        ]);
    }
}
