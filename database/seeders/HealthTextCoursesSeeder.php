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

class HealthTextCoursesSeeder extends Seeder
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

        $healthBlueprints = [
            [
                'category' => 'Santé & Soins',
                'course' => 'Hygiène hospitalière et prévention des infections',
                'description' => 'Appliquez les règles d’hygiène essentielles pour réduire les risques infectieux dans les soins.',
                'modules' => [
                    ['Principes de base', ['Chaîne de transmission', 'Hygiène des mains', 'Précautions standard']],
                    ['Organisation des soins', ['Gestion des zones propres/sales', 'Matériel et désinfection', 'Gestion du linge']],
                    ['Surveillance et amélioration', ['Signaux d’alerte', 'Traçabilité des actions', 'Audit interne hygiène']],
                ],
            ],
            [
                'category' => 'Santé Publique',
                'course' => 'Vaccination en pratique pour structures de santé',
                'description' => 'Structurer une campagne vaccinale locale, de l’organisation au suivi.',
                'modules' => [
                    ['Cadre et recommandations', ['Principes vaccinaux', 'Calendriers de référence', 'Contre-indications']],
                    ['Organisation terrain', ['Flux patients', 'Chaîne du froid', 'Gestion des stocks']],
                    ['Suivi post-vaccination', ['Surveillance des effets', 'Communication patient', 'Reporting des indicateurs']],
                ],
            ],
            [
                'category' => 'Urgences Médicales',
                'course' => 'Premiers gestes d’urgence en milieu clinique',
                'description' => 'Renforcez la capacité de réaction des équipes face aux urgences fréquentes.',
                'modules' => [
                    ['Évaluation initiale', ['Approche ABCDE', 'Signes de gravité', 'Priorisation des cas']],
                    ['Gestes immédiats', ['Stabilisation patient', 'Oxygénothérapie', 'Coordination équipe']],
                    ['Relais et documentation', ['Transmission sécurisée', 'Traçabilité', 'Retour d’expérience']],
                ],
            ],
            [
                'category' => 'Santé Maternelle',
                'course' => 'Suivi prénatal et accompagnement de la mère',
                'description' => 'Structurer un parcours prénatal sécurisant et orienté prévention.',
                'modules' => [
                    ['Consultations prénatales', ['Calendrier de suivi', 'Examens de base', 'Facteurs de risque']],
                    ['Prévention et conseils', ['Nutrition maternelle', 'Signes d’alerte grossesse', 'Plan de naissance']],
                    ['Post-partum', ['Suivi immédiat', 'Allaitement et soutien', 'Orientation en cas de complication']],
                ],
            ],
            [
                'category' => 'Pédiatrie',
                'course' => 'Prise en charge pédiatrique de premier niveau',
                'description' => 'Améliorez l’évaluation et la prise en charge initiale des enfants.',
                'modules' => [
                    ['Évaluation clinique enfant', ['Triage pédiatrique', 'Courbes de croissance', 'Red flags']],
                    ['Pathologies courantes', ['Fièvre et déshydratation', 'Infections respiratoires', 'Troubles digestifs']],
                    ['Communication parents', ['Explication du plan de soin', 'Éducation thérapeutique', 'Suivi à domicile']],
                ],
            ],
            [
                'category' => 'Santé Mentale',
                'course' => 'Accueil et orientation en santé mentale',
                'description' => 'Développez une posture adaptée pour l’accueil des patients en détresse psychique.',
                'modules' => [
                    ['Repérage initial', ['Signaux psychosociaux', 'Évaluation du risque', 'Cadre d’entretien']],
                    ['Conduite de l’entretien', ['Écoute active', 'Techniques de désescalade', 'Limites professionnelles']],
                    ['Orientation et suivi', ['Réseau de prise en charge', 'Coordination interprofessionnelle', 'Continuité des soins']],
                ],
            ],
            [
                'category' => 'Nutrition Santé',
                'course' => 'Nutrition clinique et conseil patient',
                'description' => 'Intégrer la nutrition dans la prise en charge quotidienne des patients.',
                'modules' => [
                    ['Bases de nutrition', ['Besoins nutritionnels', 'Évaluation rapide', 'Risque de dénutrition']],
                    ['Plan alimentaire', ['Objectifs réalistes', 'Adaptation au contexte', 'Suivi de l’adhésion']],
                    ['Prévention', ['Éducation nutritionnelle', 'Habitudes durables', 'Suivi des résultats']],
                ],
            ],
            [
                'category' => 'Administration Santé',
                'course' => 'Gestion administrative d’un centre de santé',
                'description' => 'Optimisez les processus administratifs pour un parcours patient fluide.',
                'modules' => [
                    ['Accueil et admission', ['Dossier patient', 'Gestion des rendez-vous', 'Qualité d’accueil']],
                    ['Gestion des flux', ['Parcours patient', 'Coordination des services', 'Réduction des délais']],
                    ['Pilotage administratif', ['Indicateurs de performance', 'Amélioration continue', 'Conformité documentaire']],
                ],
            ],
        ];

        foreach ($healthBlueprints as $courseIndex => $blueprint) {
            $category = Category::firstOrCreate([
                'slug' => Str::slug($blueprint['category']),
            ], [
                'name' => $blueprint['category'],
                'description' => 'Parcours thématiques en ' . $blueprint['category'],
            ]);

            $course = Course::updateOrCreate([
                'title' => $blueprint['course'],
                'creator_id' => $formateur->id,
            ], [
                'category_id' => $category->id,
                'description' => $blueprint['description'],
                'short_description' => $blueprint['description'],
                'detailed_description' => $blueprint['description'] . ' Formation conçue pour un usage métier dans une plateforme d’apprentissage santé.',
                'objectives' => 'Structurer les pratiques, renforcer la qualité de prise en charge et sécuriser les procédures.',
                'target_audience' => 'Professionnels de santé, encadrants, personnel de soutien clinique et administratif.',
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
                'show_on_home_carousel' => false,
                'home_carousel_order' => null,
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
            . '<p><strong>Contexte santé :</strong> ' . e($courseTitle) . ' — ' . e($moduleTitle) . '.</p>'
            . '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut dignissim ex. Vivamus efficitur, est in ullamcorper dictum, justo arcu sollicitudin sapien, et luctus arcu felis non lacus.</p>'
            . '<p>Sed gravida feugiat mauris, id feugiat libero eleifend ut. Quisque sit amet mattis purus. Mauris bibendum, justo in pulvinar pellentesque, eros leo interdum augue, vitae ultricies neque nibh vitae dolor.</p>'
            . '<ul>'
            . '<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>'
            . '<li>Praesent et lectus vel ligula pharetra feugiat.</li>'
            . '<li>Fusce feugiat justo in neque facilisis, in tempus sem malesuada.</li>'
            . '</ul>'
            . '<p>Curabitur varius, velit at ultrices feugiat, ex ipsum tempor odio, sed pulvinar massa mauris sed nibh. Integer egestas congue nulla, a suscipit magna eleifend at.</p>';
    }
}
