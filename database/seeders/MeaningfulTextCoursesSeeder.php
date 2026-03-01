<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MeaningfulTextCoursesSeeder extends Seeder
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

        $courseBlueprints = [
            [
                'category' => 'Cybersécurité',
                'course' => 'Hygiène numérique en entreprise',
                'description' => 'Adoptez les bons réflexes pour sécuriser les comptes, les postes et les échanges professionnels.',
                'modules' => [
                    ['Fondamentaux de la sécurité', ['Menaces courantes au bureau', 'Mots de passe et authentification', 'Bonnes pratiques quotidiennes']],
                    ['Protection des données', ['Classification des informations', 'Partage sécurisé des fichiers', 'Gestion des incidents']],
                    ['Mise en pratique', ['Cas type : tentative de phishing', 'Checklist de sécurité équipe', 'Plan d’amélioration continue']],
                ],
            ],
            [
                'category' => 'Conformité & Qualité',
                'course' => 'Conformité opérationnelle pour équipes terrain',
                'description' => 'Comprenez les exigences de conformité et transformez-les en routines simples pour vos équipes.',
                'modules' => [
                    ['Référentiels et obligations', ['Lecture d’un référentiel', 'Cartographie des exigences', 'Risques de non-conformité']],
                    ['Pilotage conformité', ['Mise en place des contrôles', 'Suivi des écarts', 'Traçabilité documentaire']],
                    ['Culture qualité', ['Animation des rituels qualité', 'Sensibilisation des équipes', 'Boucle d’amélioration']],
                ],
            ],
            [
                'category' => 'Gestion de Projet',
                'course' => 'Gestion de projet digital : de l’idée au déploiement',
                'description' => 'Structurez vos projets, suivez les priorités et livrez avec clarté.',
                'modules' => [
                    ['Cadrage du projet', ['Objectifs et indicateurs', 'Périmètre et parties prenantes', 'Planning initial']],
                    ['Exécution et suivi', ['Gestion des tâches', 'Coordination d’équipe', 'Gestion des risques projet']],
                    ['Livraison et bilan', ['Recette et validation', 'Conduite du changement', 'Rétrospective projet']],
                ],
            ],
            [
                'category' => 'Data & Reporting',
                'course' => 'Data literacy pour managers',
                'description' => 'Lisez, interprétez et utilisez les données pour mieux décider.',
                'modules' => [
                    ['Bases de la donnée', ['Types de données métier', 'Qualité et fiabilité', 'Biais d’interprétation']],
                    ['Indicateurs utiles', ['Construire un KPI actionnable', 'Lire un tableau de bord', 'Détecter les signaux faibles']],
                    ['Décision pilotée par la data', ['Prioriser avec les chiffres', 'Communiquer les résultats', 'Plan d’action data']],
                ],
            ],
            [
                'category' => 'Relation Client',
                'course' => 'Service client professionnel et résolution de situations sensibles',
                'description' => 'Améliorez la qualité de réponse et gérez les échanges délicats avec méthode.',
                'modules' => [
                    ['Fondamentaux relation client', ['Attentes clients B2B/B2C', 'Posture et écoute active', 'Structurer une réponse']],
                    ['Gestion des réclamations', ['Désamorcer une tension', 'Traiter une insatisfaction', 'Transformer un incident en opportunité']],
                    ['Performance du support', ['Mesurer la qualité', 'Améliorer les délais', 'Standardiser les réponses']],
                ],
            ],
            [
                'category' => 'Productivité',
                'course' => 'Organisation personnelle et efficacité au travail',
                'description' => 'Gagnez en efficacité avec des méthodes concrètes d’organisation individuelle.',
                'modules' => [
                    ['Priorisation intelligente', ['Urgent vs important', 'Planification hebdomadaire', 'Limiter la dispersion']],
                    ['Gestion du temps', ['Blocs de concentration', 'Traitement des interruptions', 'Rythme de production']],
                    ['Habitudes durables', ['Rituels d’équipe', 'Suivi de progression', 'Ajustements continus']],
                ],
            ],
            [
                'category' => 'Leadership',
                'course' => 'Leadership d’équipe pour nouveaux responsables',
                'description' => 'Développez les réflexes de pilotage humain, de communication et de décision.',
                'modules' => [
                    ['Posture de manager', ['Rôle et responsabilités', 'Communication claire', 'Donner du cadre']],
                    ['Pilotage de l’équipe', ['Fixer les objectifs', 'Suivre la performance', 'Motiver au quotidien']],
                    ['Développement des collaborateurs', ['Feedback utile', 'Coaching de proximité', 'Plan de progression']],
                ],
            ],
            [
                'category' => 'Ressources Humaines',
                'course' => 'Onboarding efficace des nouveaux collaborateurs',
                'description' => 'Concevez un parcours d’intégration clair, engageant et mesurable.',
                'modules' => [
                    ['Préparer l’arrivée', ['Checklist pré-onboarding', 'Rôles des parties prenantes', 'Plan des 30 premiers jours']],
                    ['Accompagner l’intégration', ['Parcours de formation initial', 'Suivi manager', 'Feedback précoce']],
                    ['Mesurer la réussite', ['Indicateurs d’intégration', 'Points d’ajustement', 'Capitalisation des retours']],
                ],
            ],
            [
                'category' => 'Vente',
                'course' => 'Techniques de vente consultative B2B',
                'description' => 'Structurez vos entretiens commerciaux pour vendre par la valeur.',
                'modules' => [
                    ['Comprendre le besoin client', ['Questionnement stratégique', 'Qualification des enjeux', 'Cartographie décideurs']],
                    ['Construire une proposition', ['Argumentaire orienté valeur', 'Gestion des objections', 'Scénario de closing']],
                    ['Fidélisation', ['Suivi post-vente', 'Détection d’opportunités', 'Plan de compte']],
                ],
            ],
            [
                'category' => 'IA & Productivité',
                'course' => 'Utiliser l’IA générative dans les tâches quotidiennes',
                'description' => 'Apprenez à utiliser l’IA de façon utile, responsable et mesurable dans vos activités.',
                'modules' => [
                    ['Bases de l’IA générative', ['Cas d’usage métier', 'Qualité des prompts', 'Limites à connaître']],
                    ['Production assistée', ['Rédaction et synthèse', 'Structuration d’idées', 'Automatisation légère']],
                    ['Gouvernance et sécurité', ['Confidentialité des données', 'Validation humaine', 'Cadre d’usage équipe']],
                ],
            ],
        ];

        foreach ($courseBlueprints as $courseIndex => $blueprint) {
            $category = Category::firstOrCreate([
                'slug' => Str::slug($blueprint['category']),
            ], [
                'name' => $blueprint['category'],
                'description' => 'Catégorie de formation : ' . $blueprint['category'],
            ]);

            $course = Course::updateOrCreate([
                'title' => $blueprint['course'],
                'creator_id' => $formateur->id,
            ], [
                'category_id' => $category->id,
                'description' => $blueprint['description'],
                'short_description' => $blueprint['description'],
                'detailed_description' => $blueprint['description'] . ' Ce parcours est pensé pour un usage concret sur la plateforme.',
                'objectives' => 'Acquérir les fondamentaux, appliquer des méthodes terrain et structurer une progression continue.',
                'target_audience' => 'Professionnels, équipes opérationnelles et apprenants en montée en compétence.',
                'level' => 'Debutant',
                'language' => 'FR',
                'total_duration_minutes' => 180,
                'certification_enabled' => true,
                'min_average' => 70,
                'final_evaluation_mode' => 'optional',
                'manual_validation' => false,
                'payment_mode' => 'module',
                'status' => 'approved',
                'is_active' => true,
                'is_paid' => false,
                'price' => 0,
                'show_on_home_carousel' => $courseIndex < 4,
                'home_carousel_order' => $courseIndex < 4 ? $courseIndex + 1 : null,
            ]);

            foreach ($blueprint['modules'] as $moduleOrder => $moduleData) {
                [$moduleTitle, $lessons] = $moduleData;

                $module = Module::updateOrCreate([
                    'course_id' => $course->id,
                    'title' => $moduleTitle,
                ], [
                    'description' => 'Module : ' . $moduleTitle,
                    'order' => $moduleOrder + 1,
                    'is_paid' => false,
                    'price' => 0,
                    'duration_minutes' => 60,
                    'counts_in_average' => true,
                    'required_for_cert' => true,
                    'required_for_final_eval' => false,
                    'min_score' => 60,
                    'max_attempts' => 3,
                    'content_types' => ['text'],
                ]);

                foreach ($lessons as $lessonOrder => $lessonTitle) {
                    Lesson::updateOrCreate([
                        'module_id' => $module->id,
                        'title' => $lessonTitle,
                    ], [
                        'description' => 'Leçon texte : ' . $lessonTitle,
                        'video_url' => null,
                        'content' => $this->buildLessonContent($blueprint['course'], $moduleTitle, $lessonTitle),
                        'order' => $lessonOrder + 1,
                        'is_free_preview' => $lessonOrder === 0,
                    ]);
                }
            }
        }
    }

    private function buildLessonContent(string $courseTitle, string $moduleTitle, string $lessonTitle): string
    {
        return '<h3>' . e($lessonTitle) . '</h3>'
            . '<p><strong>Contexte du cours :</strong> ' . e($courseTitle) . ' — ' . e($moduleTitle) . '.</p>'
            . '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque non arcu sit amet nibh vulputate condimentum. Integer facilisis, nibh id gravida tristique, mi orci congue ante, vitae fermentum sapien risus nec nisl.</p>'
            . '<p>Praesent euismod sem a mauris ullamcorper, eu feugiat velit convallis. Donec feugiat, turpis ut tincidunt volutpat, elit tellus eleifend est, non gravida odio leo nec lectus.</p>'
            . '<ul>'
            . '<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>'
            . '<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices.</li>'
            . '<li>Integer et velit nec purus imperdiet consequat.</li>'
            . '</ul>'
            . '<p>Aliquam erat volutpat. Aenean id varius velit. Curabitur feugiat lorem at erat sodales, at suscipit sapien efficitur. Sed posuere lectus in neque tincidunt, non efficitur justo interdum.</p>';
    }
}
