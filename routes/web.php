<?php

use App\Http\Controllers\TeacherController;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('HomePage');
})->name('home');

Route::get('/teachers', [TeacherController::class, 'index'])
    ->name('teacher.index');

Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])
    ->name('teacher.show');

Route::get('/faqs', function () {
    return Inertia::render('FaqsPage', [
        'faqs' => FaqResource::collection(
            Faq::whereIsVisibleOnFaqsPage(true)->orderBy('order')->get(),
        ),
    ]);
})->name('faqs');

Route::get('/contact', function () {
    return Inertia::render('ContactUs');
})->name('contact');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
