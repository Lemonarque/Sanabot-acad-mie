<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Louck's Health - Sanabot Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            overflow-x: hidden;
        }
        html {
            scrollbar-gutter: stable;
        }

        body { padding-top: 0 !important; }
        .sa-shell {
            background:
                radial-gradient(60rem 40rem at 10% -10%, rgba(53, 167, 178, 0.22), transparent 60%),
                radial-gradient(50rem 35rem at 90% 0%, rgba(123, 191, 100, 0.18), transparent 60%),
                linear-gradient(180deg, #f6fbfb 0%, #eef5f4 100%);
        }
        .sa-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
            box-sizing: border-box;
        }
        .sa-main { padding: 6.75rem 0 4rem; }
        .sa-stack { display: grid; gap: 2rem; }
        .sa-panel {
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.78);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
            padding: 1.5rem;
        }
        .sa-hero { display: grid; gap: 1.5rem; align-items: center; min-width: 0; }
        .sa-hero-copy { max-width: 62ch; }
        .sa-hero-image {
            width: 100%;
            max-width: 100%;
            aspect-ratio: 16 / 11;
            min-height: 300px;
            object-fit: cover;
            object-position: center 35%;
            border-radius: 1rem;
            border: 1px solid rgba(148, 163, 184, 0.25);
            display: block;
        }
        .sa-section-head { margin-bottom: 1.5rem; max-width: 70ch; }
        .sa-kicker {
            color: var(--teal);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 700;
        }
        .sa-grid-3 { display: grid; gap: 1.25rem; }
        .sa-grid-2 { display: grid; gap: 1.25rem; grid-template-columns: minmax(0, 1fr); }
        .sa-grid-4 { display: grid; gap: 1rem; }
        .sa-card {
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 1rem;
            background: white;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .sa-card-image {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            object-position: center 38%;
            display: block;
        }
        .sa-card-body { padding: 1rem 1rem 1.15rem; }
        .sa-pro-card {
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 1rem;
            background: white;
            min-height: 92px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            text-align: center;
            padding: 0.75rem;
        }
        .sa-cta {
            background: linear-gradient(135deg, rgba(53, 167, 178, 0.16), rgba(123, 191, 100, 0.14));
            display: grid;
            gap: 1.25rem;
            align-items: center;
        }
        .sa-btn-row { display: flex; flex-wrap: wrap; gap: 0.75rem; }
        .sa-footer {
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.05);
            padding: 1.75rem;
        }
        .sa-footer-grid {
            display: grid;
            gap: 1.5rem;
        }
        .sa-footer-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #0f172a;
            margin-bottom: 0.65rem;
        }
        .sa-footer-list {
            display: grid;
            gap: 0.4rem;
            color: #475569;
            font-size: 0.92rem;
        }
        .sa-footer-list a {
            color: #475569;
            text-decoration: none;
        }
        .sa-footer-list a:hover {
            color: #0f172a;
            text-decoration: underline;
        }
        .sa-footer-legal {
            margin-top: 1.2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(148, 163, 184, 0.22);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.6rem;
            color: #64748b;
            font-size: 0.82rem;
        }
        .sa-featured-grid {
            width: 100%;
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
        .sa-featured-card {
            min-height: 100%;
        }
        .sa-featured-card .sa-card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .sa-featured-card .sa-featured-meta {
            margin-top: auto;
        }
        .sa-carousel-image {
            width: 100%;
            height: 145px;
            object-fit: cover;
            object-position: center 38%;
            border-radius: 0.9rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }
        .sa-carousel-placeholder {
            width: 100%;
            height: 145px;
            border-radius: 0.9rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: linear-gradient(135deg, rgba(53, 167, 178, 0.12), rgba(123, 191, 100, 0.14));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: .8rem;
        }

        @media (min-width: 640px) {
            .sa-container {
                padding: 0 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .sa-container {
                padding: 0 2rem;
            }

            .sa-panel {
                padding: 2rem;
            }
        }

        @media (min-width: 768px) {
            .sa-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .sa-featured-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (min-width: 992px) {
            .sa-main { padding-top: 3rem; }
            .sa-hero { grid-template-columns: minmax(0, 1.02fr) minmax(0, 0.98fr); gap: 2rem; }
            .sa-hero > * { min-width: 0; }
            .sa-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; }
            .sa-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.1rem; }
            .sa-cta { grid-template-columns: 1.15fr 0.85fr; }
            .sa-footer-grid { grid-template-columns: 1.3fr 1fr 1fr 1.1fr; }
            .sa-featured-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
        }
    </style>
</head>

<body class="min-h-screen font-body text-slate-900 sa-shell">
    <div>
        <header class="nav-shell backdrop-blur fixed top-0 left-0 right-0 z-[200]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center gap-4 py-3">
                    <div class="flex-1 min-w-[220px]">
                        <a href="{{ route('home') }}">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 flex items-center justify-center">
                                    <x-application-logo class="block h-6 w-6 fill-current" style="color: var(--teal);" />
                                </div>
                                <div class="leading-tight">
                                    <div class="text-sm uppercase tracking-[0.3em] text-slate-500">Loucks Health</div>
                                    <div class="text-base font-semibold text-slate-900">Academie</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="hidden lg:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="nav-link nav-link-active text-sm font-medium">Accueil</a>
                    </div>

                    <div class="ml-auto flex items-center gap-3">
                        <a href="{{ route('login') }}" class="chip px-4 py-2 rounded-full text-sm font-semibold hover:bg-white transition">Connexion</a>
                        <a href="{{ route('register') }}" class="nav-pill px-4 py-2 rounded-full text-sm font-semibold text-slate-900 hover:shadow-md transition">Inscription</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="sa-main">
            <div class="sa-container sa-stack">
                <section class="sa-panel sa-hero">
                    <div class="sa-hero-copy space-y-7">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 border border-slate-200 text-xs uppercase tracking-[0.2em]" style="color: var(--teal);">
                        Formation & certification santé
                        </div>
                        <h1 class="font-display text-5xl lg:text-6xl leading-[1.12] text-slate-900">
                            Sanabot Academy
                            <span class="block" style="color: var(--slate);">Formez vos équipes de santé durablement.</span>
                        </h1>
                        <p class="text-lg text-slate-600">
                            Centre de formation continue pour les professionnels de santé : compétences cliniques, maîtrise des équipements,
                            outils digitaux et certifications pour renforcer la qualité des soins.
                        </p>
                        <div class="sa-btn-row">
                            <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl text-white font-semibold transition hover:shadow-lg" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Découvrir les programmes</a>
                            <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-white border border-slate-200 font-semibold hover:bg-slate-50 transition" style="color: var(--slate);">Catalogue de formations</a>
                        </div>
                    </div>

                    <img src="{{ asset('images/presentation/hero.jpg') }}"
                        alt="Formation des professionnels de santé"
                        class="sa-hero-image"
                        loading="lazy">
                </section>

                @if(isset($carouselCourses) && $carouselCourses->count() > 0)
                    <section id="cours-a-la-une" class="sa-panel" style="scroll-margin-top: calc(var(--nav-height) + 0.75rem);">
                        <div class="mb-4">
                            <div>
                                <div class="sa-kicker">Cours à la une</div>
                                <h2 class="font-display text-3xl mt-2">Les formations proposées</h2>
                            </div>
                        </div>

                        <div class="sa-featured-grid">
                            @foreach($carouselCourses as $course)
                                <article class="sa-card sa-featured-card">
                                    <div class="p-3 pb-0">
                                        @if($course->presentation_image_url)
                                            <img src="{{ $course->presentation_image_url }}" alt="Image du cours {{ $course->title }}" class="sa-carousel-image" loading="lazy">
                                        @else
                                            <div class="sa-carousel-placeholder">Image du cours</div>
                                        @endif
                                    </div>
                                    <div class="sa-card-body">
                                        <h3 class="font-display text-xl line-clamp-2">{{ $course->title }}</h3>
                                        <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $course->description }}</p>
                                        <div class="sa-featured-meta">
                                            <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                                                <span>{{ $course->modules_count }} sections</span>
                                                <span>{{ $course->enrollments_count }} inscrits</span>
                                            </div>
                                            <div class="mt-1 text-xs text-slate-500">Formateur : <span class="font-semibold text-slate-700">{{ $course->creator?->name ?? 'N/A' }}</span></div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('login') }}" class="inline-flex px-4 py-2 rounded-full text-white text-sm font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Voir le cours</a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if($carouselCourses->hasPages())
                            <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                                @if($carouselCourses->onFirstPage())
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-slate-400 text-base font-semibold">←</span>
                                @else
                                    <a href="{{ $carouselCourses->previousPageUrl() }}#cours-a-la-une" aria-label="Page précédente" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 text-base font-semibold hover:bg-slate-50 transition">←</a>
                                @endif

                                @if($carouselCourses->hasMorePages())
                                    <a href="{{ $carouselCourses->nextPageUrl() }}#cours-a-la-une" aria-label="Page suivante" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 text-base font-semibold hover:bg-slate-50 transition">→</a>
                                @else
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-slate-400 text-base font-semibold">→</span>
                                @endif
                            </div>
                        @endif
                    </section>
                @endif

                <section class="sa-panel">
                    <div class="sa-section-head">
                        <div class="sa-kicker">Modules de formation</div>
                        <h2 class="font-display text-4xl mt-2">Programmes adaptés aux métiers de la santé</h2>
                        <p class="text-slate-600 mt-2">Des parcours complets, opérationnels et applicables immédiatement sur le terrain.</p>
                    </div>

                    <div class="sa-grid-3">
                        <article class="sa-card">
                            <img src="{{ asset('images/presentation/module-1.jpg') }}" alt="Formations médicales avancées" class="sa-card-image" loading="lazy">
                            <div class="sa-card-body">
                            <h3 class="font-display text-xl">Formations Médicales Avancées</h3>
                            <p class="text-sm text-slate-600 mt-2">Protocoles cliniques actualisés, nouvelles thérapies et pratique fondée sur les preuves.</p>
                        </div>
                    </article>
                        <article class="sa-card">
                            <img src="{{ asset('images/presentation/module-2.jpg') }}" alt="Maîtrise des équipements" class="sa-card-image" loading="lazy">
                            <div class="sa-card-body">
                            <h3 class="font-display text-xl">Maîtrise des Équipements</h3>
                            <p class="text-sm text-slate-600 mt-2">Prise en main des échographes, ECG, respirateurs et autres dispositifs modernes.</p>
                        </div>
                    </article>
                        <article class="sa-card">
                            <img src="{{ asset('images/presentation/module-3.jpg') }}" alt="Outils digitaux de santé" class="sa-card-image" loading="lazy">
                            <div class="sa-card-body">
                            <h3 class="font-display text-xl">Outils Digitaux de Santé</h3>
                            <p class="text-sm text-slate-600 mt-2">Dossier médical électronique, télémédecine, cybersécurité et gestion des données patients.</p>
                        </div>
                    </article>
                        <article class="sa-card">
                            <img src="{{ asset('images/presentation/module-4.jpg') }}" alt="Formations par spécialisation" class="sa-card-image" loading="lazy">
                            <div class="sa-card-body">
                            <h3 class="font-display text-xl">Formations par Spécialisation</h3>
                            <p class="text-sm text-slate-600 mt-2">Urgences, pédiatrie, obstétrique, gériatrie, radiologie et médecine générale.</p>
                        </div>
                    </article>
                        <article class="sa-card">
                            <img src="{{ asset('images/presentation/module-5.jpg') }}" alt="Certifications reconnues" class="sa-card-image" loading="lazy">
                            <div class="sa-card-body">
                            <h3 class="font-display text-xl">Certifications Reconnues</h3>
                            <p class="text-sm text-slate-600 mt-2">Validation des compétences et attestations conformes aux exigences professionnelles.</p>
                        </div>
                    </article>
                        <article class="sa-card">
                            <img src="{{ asset('images/presentation/module-6.jpg') }}" alt="Formation continue" class="sa-card-image" loading="lazy">
                            <div class="sa-card-body">
                            <h3 class="font-display text-xl">Formation Continue</h3>
                            <p class="text-sm text-slate-600 mt-2">Mises à jour régulières, webinaires, bibliothèque médicale et communauté d’apprentissage.</p>
                        </div>
                    </article>
                    </div>
                </section>

                <section class="sa-panel" style="background: linear-gradient(180deg, rgba(122, 199, 211, 0.09), rgba(255,255,255,0.82));">
                    <div class="sa-section-head">
                        <div class="sa-kicker" style="color: var(--sky);">Professionnels formés</div>
                    <h2 class="font-display text-4xl mt-2">Parcours pour tous les profils de soins</h2>
                </div>
                    <div class="sa-grid-4">
                        <div class="sa-pro-card">🩺 <span>Médecins</span></div>
                        <div class="sa-pro-card">💉 <span>Infirmiers</span></div>
                        <div class="sa-pro-card">👶 <span>Sages-femmes</span></div>
                        <div class="sa-pro-card">🧪 <span>Techniciens médicaux</span></div>
                        <div class="sa-pro-card">💊 <span>Pharmaciens</span></div>
                        <div class="sa-pro-card">🔬 <span>Biologistes</span></div>
                        <div class="sa-pro-card">📊 <span>Gestionnaires de santé</span></div>
                        <div class="sa-pro-card">🚑 <span>Personnel d’urgence</span></div>
                    </div>
                </section>

                <section class="sa-panel" style="background: linear-gradient(180deg, rgba(123, 191, 100, 0.09), rgba(255,255,255,0.84));">
                    <div class="sa-section-head">
                        <div class="sa-kicker" style="color: var(--mint);">Avantages pour les institutions</div>
                    <h2 class="font-display text-4xl mt-2">Impact direct sur la qualité de soins</h2>
                </div>
                    <div class="sa-grid-2">
                        <article class="sa-card">
                            <div class="sa-card-body" style="padding: 1.3rem 1.35rem;">
                        <h3 class="font-display text-2xl">Amélioration de la qualité des soins</h3>
                        <p class="text-slate-600 mt-2">Des équipes formées aux meilleures pratiques réduisent les erreurs et améliorent la prise en charge.</p>
                            </div>
                        </article>
                        <article class="sa-card">
                            <div class="sa-card-body" style="padding: 1.3rem 1.35rem;">
                        <h3 class="font-display text-2xl">Rétention et motivation du personnel</h3>
                        <p class="text-slate-600 mt-2">La montée en compétences augmente l’engagement et favorise la fidélisation des talents.</p>
                            </div>
                        </article>
                        <article class="sa-card">
                            <div class="sa-card-body" style="padding: 1.3rem 1.35rem;">
                        <h3 class="font-display text-2xl">Conformité aux standards internationaux</h3>
                        <p class="text-slate-600 mt-2">Alignement progressif sur les référentiels qualité et exigences réglementaires du secteur.</p>
                            </div>
                        </article>
                        <article class="sa-card">
                            <div class="sa-card-body" style="padding: 1.3rem 1.35rem;">
                        <h3 class="font-display text-2xl">Autonomie des systèmes de santé</h3>
                        <p class="text-slate-600 mt-2">Renforcement local des compétences pour construire des capacités durables et souveraines.</p>
                            </div>
                        </article>
                    </div>
                </section>

                <section class="sa-panel sa-cta">
                    <div>
                        <div class="sa-kicker">Formez vos équipes de santé</div>
                        <h3 class="font-display text-3xl mt-2">Lancez votre programme de formation continue</h3>
                        <p class="text-slate-600 mt-2 max-w-[60ch]">Mettez en place un parcours certifiant pour vos professionnels et suivez la progression en temps réel.</p>
                    </div>
                    <div class="sa-btn-row">
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl text-white font-semibold transition hover:shadow-lg" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Voir nos programmes</a>
                        <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-white border border-slate-200 font-semibold hover:bg-slate-50 transition" style="color: var(--slate);">Planifier une session</a>
                    </div>
                </section>

                <footer class="sa-footer">
                    <div class="sa-footer-grid">
                        <div>
                            <div class="font-display text-3xl text-slate-900">Louck's Health</div>
                            <p class="text-slate-600 text-sm mt-2 max-w-sm">Pionnier de l'innovation en santé, au service de la souveraineté sanitaire et de la montée en compétences des professionnels.</p>
                        </div>

                        <div>
                            <div class="sa-footer-title">Solutions</div>
                            <div class="sa-footer-list">
                                <a href="{{ route('home') }}">Sanabot Academy</a>
                                <a href="{{ route('login') }}">Catalogue de formations</a>
                                <a href="{{ route('register') }}">Espace inscription</a>
                            </div>
                        </div>

                        <div>
                            <div class="sa-footer-title">Entreprise</div>
                            <div class="sa-footer-list">
                                <a href="#">À propos</a>
                                <a href="#">Solutions institutionnelles</a>
                                <a href="#">Contact</a>
                            </div>
                        </div>

                        <div>
                            <div class="sa-footer-title">Contact institutionnel</div>
                            <div class="sa-footer-list">
                                <div>contact@louckshealth.com</div>
                                <div>+33 1 XX XX XX XX</div>
                                <div>Paris, France</div>
                            </div>
                        </div>
                    </div>

                    <div class="sa-footer-legal">
                        <div>© {{ date('Y') }} Louck's Health. Tous droits réservés.</div>
                        <div class="flex gap-3">
                            <a href="#" class="hover:underline">Politique de confidentialité</a>
                            <a href="#" class="hover:underline">Mentions légales</a>
                            <a href="#" class="hover:underline">Plan du site</a>
                        </div>
                    </div>
                </footer>
            </div>
        </main>
    </div>
</body>
</html>
