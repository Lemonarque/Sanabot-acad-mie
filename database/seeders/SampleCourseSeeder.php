<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Progress;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class SampleCourseSeeder extends Seeder
{
    public function run(): void
    {
        $formateurRole = Role::where('name', 'formateur')->first();
        $apprenantRole = Role::where('name', 'apprenant')->first();

        $formateur = User::firstOrCreate([
            'email' => 'formateur@sanabot.com',
        ], [
            'name' => 'Formateur Expert',
            'password' => bcrypt('formateur1234'),
            'role_id' => $formateurRole?->id,
        ]);

        $apprenant = User::firstOrCreate([
            'email' => 'apprenant@sanabot.com',
        ], [
            'name' => 'Apprenant Test',
            'password' => bcrypt('apprenant1234'),
            'role_id' => $apprenantRole?->id,
        ]);

        $category = Category::firstOrCreate([
            'slug' => 'ia-sante',
        ], [
            'name' => 'IA & Sante',
            'description' => 'Formations liees a la sante et l\'IA.',
        ]);

        $course = Course::updateOrCreate([
            'title' => 'Bases de Laravel',
            'creator_id' => $formateur->id,
        ], [
            'category_id' => $category->id,
            'description' => 'Apprenez les bases de Laravel et la structure d\'une application.',
            'short_description' => 'Un parcours pour demarrer avec Laravel.',
            'detailed_description' => 'Comprendre les routes, controleurs, vues et la structure MVC.',
            'objectives' => 'Installer Laravel, comprendre les routes, modeles et vues.',
            'target_audience' => 'Etudiants et developpeurs debutants',
            'level' => 'Debutant',
            'language' => 'FR',
            'total_duration_minutes' => 180,
            'certification_enabled' => true,
            'min_average' => 75,
            'final_evaluation_mode' => 'optional',
            'manual_validation' => false,
            'payment_mode' => 'module',
            'status' => 'approved',
            'is_active' => true,
        ]);

        $module1 = Module::updateOrCreate([
            'course_id' => $course->id,
            'title' => 'Introduction',
        ], [
            'description' => 'Presentation du framework et de l\'architecture MVC.',
            'order' => 1,
            'is_paid' => false,
            'price' => 0,
            'duration_minutes' => 60,
            'counts_in_average' => true,
            'required_for_cert' => true,
            'required_for_final_eval' => false,
            'min_score' => 60,
            'max_attempts' => 3,
            'content_types' => ['video', 'quiz'],
        ]);

        $module2 = Module::updateOrCreate([
            'course_id' => $course->id,
            'title' => 'Routing et Controllers',
        ], [
            'description' => 'Comprendre le systeme de routes et les controleurs.',
            'order' => 2,
            'is_paid' => true,
            'price' => 5000,
            'duration_minutes' => 90,
            'counts_in_average' => true,
            'required_for_cert' => true,
            'required_for_final_eval' => true,
            'min_score' => 70,
            'max_attempts' => 3,
            'content_types' => ['video', 'quiz'],
        ]);

        Lesson::updateOrCreate([
            'module_id' => $module1->id,
            'title' => 'Pourquoi Laravel ?',
        ], [
            'description' => 'Les atouts du framework pour des projets modernes.',
            'content' => 'Laravel simplifie le developpement grace a un ecosysteme complet.',
            'order' => 1,
        ]);

        Lesson::updateOrCreate([
            'module_id' => $module2->id,
            'title' => 'Premiere route',
        ], [
            'description' => 'Definir une route et retourner une vue.',
            'content' => 'Les routes se declarent dans routes/web.php.',
            'order' => 1,
        ]);

        $quiz = Quiz::firstOrCreate([
            'module_id' => $module2->id,
            'title' => 'Quiz Routing',
        ], [
            'min_score' => 1,
            'max_attempts' => 3,
        ]);

        $question = Question::firstOrCreate([
            'quiz_id' => $quiz->id,
            'content' => 'Dans quel fichier se declarent les routes web ?',
        ]);

        Answer::firstOrCreate([
            'question_id' => $question->id,
            'content' => 'routes/web.php',
        ], [
            'is_correct' => true,
        ]);

        Answer::firstOrCreate([
            'question_id' => $question->id,
            'content' => 'config/app.php',
        ], [
            'is_correct' => false,
        ]);

        $enrollment = Enrollment::firstOrCreate([
            'user_id' => $apprenant->id,
            'course_id' => $course->id,
        ]);

        foreach ([$module1, $module2] as $module) {
            Progress::firstOrCreate([
                'enrollment_id' => $enrollment->id,
                'module_id' => $module->id,
            ], [
                'validated' => false,
                'score' => null,
            ]);
        }

        Certificate::firstOrCreate([
            'enrollment_id' => $enrollment->id,
        ], [
            'pdf_path' => null,
            'issued_at' => null,
        ]);
    }
}
