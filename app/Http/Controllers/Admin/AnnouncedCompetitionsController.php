<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AnnouncedCompetition\AnnouncedCompetitionRequest;
use Illuminate\Support\Str;
use App\Models\AttachedTests;
use App\Models\Competition;
use App\Models\CompetitionDepartment;
use App\Models\Department;
use App\Models\Test;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncedCompetitionsController extends Controller
{
    public function index()
    {
        if (backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key'))) {
            $competitions = collect();
            backpack_user()->department->each(function ($department, $key) use ($competitions) {
                $potentialCompetitions = CompetitionDepartment::where('department_id', $department->id)->get();
                $potentialCompetitions->each(function ($potentialCompetition, $key) use ($competitions) {
                    if (!$competitions->pluck('competition_id')->contains($potentialCompetition->competition_id)) {
                        $competitions->push($potentialCompetition);
                    }
                });
            });
            $competitions = $competitions->map(function ($competition, $key) {
                return $competition->competition;
            });
            $competitions = new LengthAwarePaginator($competitions, $competitions->count(), 9, null, [
                'path' => route('announcedCompetitions'),
                'pageName' => 'page',
            ]);
        } else  $competitions = Competition::paginate(9);
        return view(
            'announcedCompetition.announced_competitions',
            [
                'competitions' => $competitions,
            ]
        );
    }

    public function announcedCompetition($id)
    {
        $departments = collect();
        if (CompetitionDepartment::where('competition_id', $id)->get()->count() == 0) abort(404);
        if (backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key'))) {
            backpack_user()->department->pluck('id')->each(function ($value, $key) use ($departments, $id) {
                if (CompetitionDepartment::where('competition_id', $id)->where('department_id', $value)->first())
                    $departments->push(CompetitionDepartment::where('competition_id', $id)->where('department_id', $value)->first());
            });
            if ($departments->count() == 0) abort(403);
        } else
            $departments =  CompetitionDepartment::where('competition_id', $id)->get();
        return view('announcedCompetition.announced_competition_departments', [
            'departments' => $departments,
            'attachedTests' => AttachedTests::class,
            'competition' => Competition::find($id),
        ]);
    }


    public function showTestsToAddByDepartment($id, $department_id)
    {
        if (CompetitionDepartment::where('competition_id', $id)->where('department_id', $department_id)->get()->count() == 0) abort(404);
        if (backpack_user()->hasPermissionTo(config('permissions.lecturer.view.key')))
            if (!backpack_user()->department->pluck('id')->contains($department_id)) abort(403);

        return view('announcedCompetition.announced_competition', [
            'competition' => Competition::find($id),
            'userTests' => Test::where('user_id', auth()->id())->get(),
            'attachedTest' => AttachedTests::where('department_id', $department_id)->where('competition_id', $id)->first(),
            'department' => Department::find($department_id),
        ]);
    }

    public function attachTest($id, AnnouncedCompetitionRequest $request)
    {
        $attachedTest = AttachedTests::where('competition_id', $id)->where('competition_id', $request->get('department_id'))->first();
        if (!$attachedTest && $request->get('test_id')) {
            $attachTest = new AttachedTests();
            $attachTest->competition_id = $request->get('competition_id');

            $attachTest->department_id = $request->get('department_id');
            $attachTest->test_id = $request->get('test_id');

            $attachTest->save();
            return redirect(route('announcedCompetition', $request->get('competition_id')));
        }
        else if($attachedTest) $attachedTest->delete();
        return redirect(route('announcedCompetition', $request->get('competition_id')));
    }
}
