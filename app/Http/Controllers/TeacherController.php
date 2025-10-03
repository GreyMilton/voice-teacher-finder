<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    /**
     * Display a list of teachers.
     */
    public function index(): Response
    {
        return Inertia::render('TeacherIndex', [
            'teachers' => TeacherResource::collection(Teacher::authorised()->get()),
        ]);
    }

    /**
     * Show the listing for a given teacher.
     */
    public function show(Teacher $teacher): Response
    {
        if (! $teacher->isAuthorised) {
            abort(404);
        }

        return Inertia::render('TeacherShow', [
            'teacher' => $teacher->load([
                'firstAuthorisationCohort',
                'territoryOfOrigin',
                'territoryOfResidence',
                'instruments',
                'languagesSung',
                'languagesTeachesIn',
                'tuitionLocations',
                'updateCohorts',
            ])->toResource(),
        ]);
    }
}
