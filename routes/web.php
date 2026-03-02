<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Courses\Manage as CoursesManage;
use App\Livewire\Courses\Builder as CoursesBuilder;
use App\Livewire\Modules\Manage as ModulesManage;
use App\Livewire\Lessons\Manage as LessonsManage;
use App\Livewire\Lessons\Editor as LessonsEditor;
use App\Livewire\Resources\Manage as ResourcesManage;
use App\Livewire\Quiz\Manage as QuizManage;
use App\Livewire\Quiz\QuestionsManage as QuizQuestionsManage;
use App\Livewire\Quiz\AnswersManage as QuizAnswersManage;
use App\Livewire\Quiz\Builder as QuizBuilder;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\UsersManage as AdminUsersManage;
use App\Livewire\Admin\CoursesManage as AdminCoursesManage;
use App\Livewire\Admin\CategoriesManage as AdminCategoriesManage;
use App\Livewire\Admin\EnrollmentsManage as AdminEnrollmentsManage;
use App\Livewire\Admin\PaymentsManage as AdminPaymentsManage;
use App\Livewire\Admin\CertificatesManage as AdminCertificatesManage;
use App\Livewire\Admin\InstitutionsManage as AdminInstitutionsManage;
use App\Livewire\Courses\Catalogue as CoursesCatalogue;
use App\Livewire\Courses\Show as CoursesShow;
use App\Livewire\Dashboard\UserDashboard;
use App\Livewire\Institution\CourseRequests as InstitutionCourseRequests;
use App\Livewire\Institution\Dashboard as InstitutionDashboard;
use App\Livewire\Institution\Learners as InstitutionLearners;
use App\Livewire\Institution\Reporting as InstitutionReporting;
use App\Livewire\Progress\Track as ProgressTrack;
use App\Livewire\Certificate\FormateurManage as FormateurCertificatesManage;
use App\Livewire\Quiz\TakeQuiz as QuizTakeQuiz;
use App\Livewire\Lessons\Show as LessonsShow;
use App\Livewire\Modules\Show as ModulesShow;
use App\Livewire\Payment\Checkout as PaymentCheckout;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CourseImageController;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Course;

// Page d'accueil
Route::get('/', function () {
    return rescue(
        fn () => view('welcome', [
            'carouselCourses' => Course::query()
                ->with(['creator'])
                ->withCount(['modules', 'enrollments'])
                ->where('show_on_home_carousel', true)
                ->whereIn('status', ['approved', 'validated'])
                ->where('is_active', true)
                ->orderByRaw('CASE WHEN home_carousel_order IS NULL THEN 1 ELSE 0 END')
                ->orderBy('home_carousel_order')
                ->latest()
                ->paginate(5),
        ]),
        response(
            '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Sanabot Academy</title></head><body style="font-family:Arial,sans-serif;padding:24px;"><h1>Sanabot Academy</h1><p>L\'application est en cours d\'initialisation.</p><p><a href="/login">Connexion</a> · <a href="/register">Inscription</a></p></body></html>',
            200,
            ['Content-Type' => 'text/html; charset=UTF-8']
        ),
        report: false
    );
})->name('home');

Route::view('/profil', 'profile')->middleware('auth')->name('profile');

Route::get('/courses/{course}/image', CourseImageController::class)->name('courses.image');

Route::get('/catalogue', CoursesCatalogue::class)->middleware('auth')->name('courses.catalogue');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user || ! $user->role) {
        return redirect()->route('home');
    }
    return match ($user->role->name) {
        'admin' => redirect()->route('admin.dashboard'),
        'formateur' => redirect()->route('courses.manage'),
        'institution' => redirect()->route('institution.dashboard'),
        default => redirect()->route('apprenant.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// Interfaces formateur (Livewire)
Route::middleware(['auth', 'role:formateur'])->prefix('formateur')->group(function () {
    Route::get('/cours', CoursesManage::class)->name('courses.manage');
    Route::get('/cours/builder', CoursesBuilder::class)->name('courses.builder');
    Route::get('/cours/{courseId}/builder', CoursesBuilder::class)->name('courses.builder.edit');
    Route::get('/cours/{courseId}/modules', ModulesManage::class)->name('modules.manage');
    Route::get('/modules/{moduleId}/lessons', LessonsManage::class)->name('lessons.manage');
    Route::get('/modules/{moduleId}/lessons/create', LessonsEditor::class)->name('lessons.create');
    Route::get('/lessons/{lessonId}/edit', LessonsEditor::class)->name('lessons.edit');
    Route::get('/lessons/{lessonId}/resources', ResourcesManage::class)->name('resources.manage');
    Route::get('/cours/{courseId}/evaluation-finale', QuizBuilder::class)->name('quiz.course.builder');
    Route::get('/modules/{moduleId}/quiz/builder', QuizBuilder::class)->name('quiz.builder');
    Route::get('/modules/{moduleId}/quiz', QuizManage::class)->name('quiz.manage');
    Route::get('/quiz/{quizId}/questions', QuizQuestionsManage::class)->name('quiz.questions.manage');
    Route::get('/quiz/questions/{questionId}/answers', QuizAnswersManage::class)->name('quiz.answers.manage');
    Route::get('/cours/{courseId}/certificats', FormateurCertificatesManage::class)->name('courses.certificates.manage');
});

// Interfaces admin (Livewire)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/users', AdminUsersManage::class)->name('admin.users.manage');
    Route::get('/courses', AdminCoursesManage::class)->name('admin.courses.manage');
    Route::get('/categories', AdminCategoriesManage::class)->name('admin.categories.manage');
    Route::get('/enrollments', AdminEnrollmentsManage::class)->name('admin.enrollments.manage');
    Route::get('/payments', AdminPaymentsManage::class)->name('admin.payments.manage');
    Route::get('/certificates', AdminCertificatesManage::class)->name('admin.certificates.manage');
    Route::get('/institutions', AdminInstitutionsManage::class)->name('admin.institutions.manage');
});

Route::middleware(['auth', 'role:institution'])->prefix('institution')->group(function () {
    Route::get('/dashboard', InstitutionDashboard::class)->name('institution.dashboard');
    Route::get('/apprenants', InstitutionLearners::class)->name('institution.learners');
    Route::get('/demandes-cours', InstitutionCourseRequests::class)->name('institution.course.requests');
    Route::get('/reporting', InstitutionReporting::class)->name('institution.reporting');
});

// Interfaces apprenant (Livewire)
Route::middleware(['auth', 'role:apprenant'])->prefix('apprenant')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('apprenant.dashboard');
    Route::get('/cours', CoursesCatalogue::class)->name('apprenant.courses.catalogue');
    Route::get('/cours/{id}', CoursesShow::class)->name('apprenant.courses.show');
    Route::get('/modules/{id}', ModulesShow::class)->name('apprenant.modules.show');
    Route::get('/lecons/{id}', LessonsShow::class)->name('apprenant.lessons.show');
    Route::get('/progression', ProgressTrack::class)->name('apprenant.progress');
    Route::get('/modules/{moduleId}/quiz/{id}', QuizTakeQuiz::class)->name('apprenant.quiz.take');
    Route::get('/cours/{courseId}/evaluation-finale/{id}', QuizTakeQuiz::class)->name('apprenant.quiz.course.take');
    Route::get('/quiz/{id}/passer', function ($id) {
        $quiz = Quiz::findOrFail($id);

        if ($quiz->module_id) {
            return redirect()->route('apprenant.quiz.take', [
                'moduleId' => $quiz->module_id,
                'id' => $quiz->id,
            ]);
        }

        return redirect()->route('apprenant.quiz.course.take', [
            'courseId' => $quiz->course_id,
            'id' => $quiz->id,
        ]);
    });
});

// Routes de paiement
Route::middleware(['auth'])->group(function () {
    Route::get('/cours/{courseId}/checkout', PaymentCheckout::class)->name('payment.checkout');
    Route::get('/paiement/succes', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/paiement/annulation', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Webhook Fedapay (sans authentification)
Route::post('/webhook/fedapay', [PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
