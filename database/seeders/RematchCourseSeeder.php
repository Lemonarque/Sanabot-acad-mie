<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class RematchCourseSeeder extends Seeder
{
    public function run(): void
    {
        $formateurRole = Role::where('name', 'formateur')->first();

        $formateur = User::firstOrCreate([
            'email' => 'formateur@sanabot.com',
        ], [
            'name' => 'Formateur Expert',
            'password' => bcrypt('formateur1234'),
            'role_id' => $formateurRole?->id,
        ]);

        $category = Category::firstOrCreate([
            'slug' => 'cyber-securite',
        ], [
            'name' => 'Cyber-securite',
            'description' => 'Parcours cyber et securite numerique.',
        ]);

        $course = Course::updateOrCreate([
            'title' => 'Rematch - De debutant a avance',
            'creator_id' => $formateur->id,
        ], [
            'category_id' => $category->id,
            'description' => 'Apprenez Rematch pas a pas : bases du jeu, matchmaking et techniques avancees.',
            'short_description' => 'Un parcours complet pour progresser sur Rematch.',
            'detailed_description' => 'Bases, matchmaking, techniques avancees et bonnes pratiques.',
            'objectives' => 'Comprendre les regles, optimiser le matchmaking, appliquer des strategies avancees.',
            'target_audience' => 'Joueurs debutants et intermediaires',
            'level' => 'Intermediaire',
            'language' => 'FR',
            'total_duration_minutes' => 120,
            'certification_enabled' => true,
            'min_average' => 75,
            'final_evaluation_mode' => 'optional',
            'manual_validation' => false,
            'payment_mode' => 'module',
            'status' => 'approved',
            'is_active' => true,
        ]);

        $section1 = Module::updateOrCreate([
            'course_id' => $course->id,
            'title' => 'Bases du jeu',
        ], [
            'description' => 'Prendre en main Rematch et comprendre les fondamentaux.',
            'order' => 1,
            'is_paid' => false,
            'price' => 0,
            'duration_minutes' => 40,
            'counts_in_average' => true,
            'required_for_cert' => true,
            'required_for_final_eval' => false,
            'min_score' => 60,
            'max_attempts' => 3,
            'content_types' => ['video'],
        ]);

        $section2 = Module::updateOrCreate([
            'course_id' => $course->id,
            'title' => 'Matchmaking et classement',
        ], [
            'description' => 'Comprendre les rangs et progresser efficacement.',
            'order' => 2,
            'is_paid' => true,
            'price' => 5000,
            'duration_minutes' => 40,
            'counts_in_average' => true,
            'required_for_cert' => true,
            'required_for_final_eval' => true,
            'min_score' => 70,
            'max_attempts' => 3,
            'content_types' => ['video'],
        ]);

        $section3 = Module::updateOrCreate([
            'course_id' => $course->id,
            'title' => 'Techniques avancees',
        ], [
            'description' => 'Maitriser les techniques pour gagner plus regulierement.',
            'order' => 3,
            'is_paid' => true,
            'price' => 5000,
            'duration_minutes' => 40,
            'counts_in_average' => true,
            'required_for_cert' => true,
            'required_for_final_eval' => true,
            'min_score' => 75,
            'max_attempts' => 3,
            'content_types' => ['video'],
        ]);

        Lesson::updateOrCreate([
            'module_id' => $section1->id,
            'title' => 'Bien debuter sur Rematch',
        ], [
            'description' => 'Prise en main et bases essentielles.',
            'video_url' => 'https://www.youtube.com/embed/GpybAQJEcFE',
            'content' => '<p><span style="color:#35a7b2; font-weight:600;">Objectif :</span> Comprendre les bases du jeu et ses regles.</p><ul><li><span style="color:#7bbf64; font-weight:600;">Mecanique</span> : controle, timing, espace.</li><li><span style="color:#f4c55e; font-weight:600;">Placement</span> : zones fortes et transitions.</li><li><span style="color:#f08ea3; font-weight:600;">Priorite</span> : decision rapide et lecture du jeu.</li></ul>',
            'order' => 1,
        ]);

        Lesson::updateOrCreate([
            'module_id' => $section2->id,
            'title' => 'Comment fonctionne le matchmaking',
        ], [
            'description' => 'Systeme de matchmaking et progression.',
            'video_url' => 'https://www.youtube.com/embed/9MjGWsyHMNk',
            'content' => '<p><span style="color:#35a7b2; font-weight:600;">Comprendre le matchmaking :</span> comment le systeme evalue votre niveau.</p><ol><li><span style="color:#7bbf64; font-weight:600;">Regles</span> : elo, historique et stabilite.</li><li><span style="color:#f4c55e; font-weight:600;">Progression</span> : regularite et adaptation.</li><li><span style="color:#f08ea3; font-weight:600;">Conseil</span> : jouer en equipe stable pour progresser.</li></ol>',
            'order' => 1,
        ]);

        Lesson::updateOrCreate([
            'module_id' => $section3->id,
            'title' => 'Techniques avancees pour gagner',
        ], [
            'description' => 'Strategies avancees et habitudes de pros.',
            'video_url' => 'https://www.youtube.com/embed/w7WSaFQbdt0',
            'content' => '<p><span style="color:#35a7b2; font-weight:600;">Techniques avancees :</span> lecture, feintes et tempo.</p><ul><li><span style="color:#7bbf64; font-weight:600;">Tempo</span> : accelere ou ralentis le rythme.</li><li><span style="color:#f4c55e; font-weight:600;">Feintes</span> : force les erreurs.</li><li><span style="color:#f08ea3; font-weight:600;">Synergie</span> : communication et roles clairs.</li></ul>',
            'order' => 1,
        ]);

        $quiz = Quiz::updateOrCreate([
            'module_id' => $section3->id,
            'title' => 'Quiz final - Rematch',
        ], [
            'min_score' => 3,
            'max_attempts' => 3,
        ]);

        $questions = [
            [
                'content' => 'Quel facteur est le plus important pour progresser durablement sur Rematch ?',
                'answers' => [
                    ['content' => 'Changer de role a chaque partie', 'is_correct' => false],
                    ['content' => 'Jouer regulierement avec analyse de ses erreurs', 'is_correct' => true],
                    ['content' => 'Jouer uniquement en solo sans communication', 'is_correct' => false],
                    ['content' => 'Ignorer le positionnement', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Dans le matchmaking, que signifie une progression de rang stable ?',
                'answers' => [
                    ['content' => 'Une chance aleatoire sans lien avec le niveau', 'is_correct' => false],
                    ['content' => 'Une regularite des performances dans le temps', 'is_correct' => true],
                    ['content' => 'Uniquement le nombre de parties jouees', 'is_correct' => false],
                    ['content' => 'Le choix d\'un skin rare', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Quelle habitude aide le plus a gagner des duels decisifs ?',
                'answers' => [
                    ['content' => 'Forcer chaque action au maximum de vitesse', 'is_correct' => false],
                    ['content' => 'Alterner tempo et feintes selon la situation', 'is_correct' => true],
                    ['content' => 'Rester immobile en permanence', 'is_correct' => false],
                    ['content' => 'Ignorer les informations de l\'equipe', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Quel est le meilleur usage de la communication en equipe ?',
                'answers' => [
                    ['content' => 'Donner des informations courtes et utiles en temps reel', 'is_correct' => true],
                    ['content' => 'Parler uniquement apres la partie', 'is_correct' => false],
                    ['content' => 'Eviter toute communication pour rester concentre', 'is_correct' => false],
                    ['content' => 'Parler sans prioriser les infos importantes', 'is_correct' => false],
                ],
            ],
            [
                'content' => 'Quand un module est valide dans ce parcours, cela indique que :',
                'answers' => [
                    ['content' => 'Le chapitre ou le quiz a atteint le niveau attendu', 'is_correct' => true],
                    ['content' => 'Le cours entier est automatiquement termine', 'is_correct' => false],
                    ['content' => 'Aucune progression n\'est enregistree', 'is_correct' => false],
                    ['content' => 'Le classement global est force au maximum', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $question = Question::updateOrCreate([
                'quiz_id' => $quiz->id,
                'content' => $questionData['content'],
            ]);

            foreach ($questionData['answers'] as $answerData) {
                Answer::updateOrCreate([
                    'question_id' => $question->id,
                    'content' => $answerData['content'],
                ], [
                    'is_correct' => $answerData['is_correct'],
                ]);
            }
        }
    }
}
