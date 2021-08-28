<?php

namespace App\Http\Controllers\Admin;

use App\Exports\exportSubmittedTestsByCompetition;
use App\Exports\exportSubmittedTestsByDepartment;
use App\Exports\SubmittedTestsExport;
use App\Http\Controllers\Controller;
use App\Models\AttachedTests;
use App\Models\Competition;
use App\Models\CompetitionDepartment;
use App\Models\Department;
use App\Models\SubmittedTest;
use App\Models\SubmittedTestAnswers;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class SubmittedTestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function competitions()
    {
        if(backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key'))) {
            $competitions = collect();
            backpack_user()->department->each(function($department, $key) use($competitions) {
                $potentialCompetitions = CompetitionDepartment::where('department_id', $department->id)->get();
                $potentialCompetitions->each(function($potentialCompetition, $key) use($competitions) {
                    if(!$competitions->pluck('competition_id')->contains($potentialCompetition->competition_id)) {
                        $competitions->push($potentialCompetition);
                    }
                });
            });
            $competitions = $competitions->map(function($competition, $key) { return $competition->competition; });
            $competitions = new LengthAwarePaginator($competitions, $competitions->count(), 9, null, [
                'path' => route('announcedCompetitions'),
                'pageName' => 'page',
            ]);
        }
        else  $competitions = Competition::paginate(9);
        return view('test.submitted_tests.competition',[
            'competitions' => $competitions,
        ]);
    }
    public function exportByDepartment($department_id, $competition_id) {
        return Excel::download(new exportSubmittedTestsByDepartment($department_id, $competition_id), 'testsByDepartment.xlsx');
    }

    public function exportByCompetition($competition_id) {
        return Excel::download(new exportSubmittedTestsByCompetition($competition_id), 'testByCompetition.xlsx');
    }
    /**
     * Display All Departments Attached To Competition
     *
     */
    public function departments($competition_id)
    {
        $departments = collect();
        if(CompetitionDepartment::where('competition_id', $competition_id)->get()->count() == 0) abort(404);
        if (backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key'))) {
            backpack_user()->department->pluck('id')->each(function ($value, $key) use ($departments, $competition_id) {
                if(CompetitionDepartment::where('competition_id', $competition_id)->where('department_id', $value)->first())
                    $departments->push(CompetitionDepartment::where('competition_id', $competition_id)->where('department_id', $value)->first());
            });
            if($departments->count() == 0) abort(403);
        }
        else
            $departments =  CompetitionDepartment::where('competition_id', $competition_id)->get();
        return view('test/submitted_tests/department',
        [
            'departments' => $departments,
            'attachedTests' => AttachedTests::class,
            'competition_id' => $competition_id,
        ]);
    }

    /**
     * Show all the submitted tests of department/competition.
     *
     */
    public function tests($competition_id, $department_id)
    {
        if(CompetitionDepartment::where('competition_id', $competition_id)->where('department_id', $department_id)->get()->count() == 0) abort(404);
        if (backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key')))
            if (!backpack_user()->department->pluck('id')->contains($department_id)) abort(403);

        return view('test/submitted_tests/tests', [
            'submittedTests' => SubmittedTest::where('department_id', $department_id)->where('competition_id', $competition_id)->paginate(9),
            'competition_id' => $competition_id,
            'department_id' => $department_id,
        ]);
    }

    /**
     * See the submitted Test
     *
     */
    public function test($competition_id, $department_id, $submitted_test_id)
    {
        if(CompetitionDepartment::where('competition_id', $competition_id)->where('department_id', $department_id)->get()->count() == 0) abort(404);
        if (backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key')))
            if (!backpack_user()->department->pluck('id')->contains($department_id)) abort(403);

        $exceptionKeys = ['department_id', 'competition_id', 'test_id', 'updated_at', 'selection_status'];
        $submittedTest = collect(SubmittedTest::find($submitted_test_id))->filter(function($value, $key) use($exceptionKeys) {
            return !in_array($key, $exceptionKeys);
        });
        $submittedTestAnswers = collect(SubmittedTest::find($submitted_test_id)->answers);
        $answers = [];
        foreach($submittedTestAnswers as $submittedTestAnswer) {
            if(isset($answers[$submittedTestAnswer->question_name]))  array_push($answers[$submittedTestAnswer->question_name], $submittedTestAnswer);
            else $answers[$submittedTestAnswer->question_name] = [$submittedTestAnswer];
        }
        return view('/test/submitted_tests/test', [
            'previousPageLink' => route('submittedTests.tests',[$competition_id, $department_id]),
            'test' => $submittedTest,
            'answers' => $answers,
            'fullTest' => SubmittedTest::find($submitted_test_id)
        ]);
    }
}
