<?php

use App\Models\Teacher;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('HomePage');
})->name('home');

Route::get('/teachers', function () {
    return Inertia::render('TeacherIndex');
})->name('teacher.index');

Route::get('/teachers/{teacher}', function (Teacher $teacher) {
    return Inertia::render('TeacherShow', [
        'teacher' => $teacher->load([
            'authorisationCohort',
            'countryOfOrigin',
            'countryOfResidence',
            'instruments',
            'languagesSung',
            'languagesTeachesIn',
            'tuitionLocations.country',
            'updateCohorts',
        ])->toResource(),
    ]);
})->name('teacher.show');

Route::get('/faqs', function () {
    return Inertia::render('FaqsPage');
})->name('faqs');

Route::get('/contact', function () {
    return Inertia::render('ContactUs');
})->name('contact');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
