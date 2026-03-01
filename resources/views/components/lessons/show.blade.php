<div class="min-h-screen aurora-bg py-10">
    <style>
        .lesson-style-reader {
            outline: none;
            min-height: 220px;
        }

        .lesson-style-reader.is-eraser {
            cursor: cell;
        }

        .lesson-style-workspace {
            display: grid;
            grid-template-columns: 64px minmax(0, 1fr);
            gap: 0.85rem;
            align-items: start;
        }

        .lesson-style-toolbar {
            position: sticky;
            top: 0.5rem;
            z-index: 5;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(6px);
            border: 1px solid rgb(226 232 240);
            border-radius: 1rem;
            padding: 0.5rem 0.4rem;
        }

        .lesson-style-toolbar-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            align-items: center;
        }

        .lesson-style-toolbar-btn {
            border: 1px solid rgb(203 213 225);
            border-radius: 0.8rem;
            width: 2.2rem;
            height: 2.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgb(51 65 85);
            background: white;
            transition: all 0.15s ease;
        }

        .lesson-style-toolbar-btn:hover {
            background: rgb(248 250 252);
        }

        .lesson-style-toolbar-btn.is-active {
            background: rgb(15 23 42);
            color: white;
            border-color: rgb(15 23 42);
        }

        .lesson-style-toolbar-select {
            border: 1px solid rgb(203 213 225);
            border-radius: 0.8rem;
            width: 2.2rem;
            height: 2.2rem;
            padding: 0;
            text-align: center;
            font-size: 0.68rem;
            font-weight: 600;
            color: rgb(51 65 85);
            background: white;
        }

        .lesson-style-status {
            font-size: 0.72rem;
            color: rgb(100 116 139);
        }

        @media (max-width: 900px) {
            .lesson-style-workspace {
                grid-template-columns: 1fr;
            }

            .lesson-style-toolbar {
                position: static;
                padding: 0.5rem;
            }

            .lesson-style-toolbar-group {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: flex-start;
            }

            .lesson-style-toolbar-btn,
            .lesson-style-toolbar-select {
                width: auto;
                min-width: 2.2rem;
                padding: 0.35rem 0.6rem;
            }
        }

        .learner-lesson-layout {
            display: block;
        }

        @media (min-width: 1024px) {
            .learner-lesson-layout {
                display: grid !important;
                grid-template-columns: 320px minmax(0, 1fr) !important;
                gap: 2rem !important;
                align-items: start !important;
            }

            .learner-lesson-sidebar {
                position: sticky !important;
                top: 6rem !important;
                max-height: calc(100vh - 7rem) !important;
                overflow-y: auto !important;
            }
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="learner-lesson-layout">
            <aside class="learner-lesson-sidebar glass-card rounded-3xl p-5 h-fit">
                <div class="mb-4">
                    <div>
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Cours</div>
                        <h3 class="font-display text-lg mt-2 text-slate-900">{{ $course->title }}</h3>
                        <div class="text-sm text-slate-500 mt-1">Formateur : <span class="font-semibold text-slate-700">{{ $course->creator->name ?? 'N/A' }}</span></div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white/80 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-200">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Table des matieres</div>
                    </div>
                    <div class="p-3 space-y-3 lg:max-h-[calc(100vh-18rem)] lg:overflow-y-auto">
                        @foreach($course->modules->sortBy('order') as $m)
                            <div class="rounded-2xl border transition {{ $m->id === $module->id ? 'border-slate-300 bg-white' : 'border-slate-200 bg-white/70' }} p-3">
                                <div class="font-semibold {{ $m->id === $module->id ? 'text-slate-900' : 'text-slate-700' }}">{{ $m->title }}</div>
                                <div class="mt-2 space-y-2 text-sm">
                                    @foreach($m->lessons->sortBy('order') as $item)
                                        <a href="{{ route('apprenant.lessons.show', $item->id) }}"
                                           class="flex items-center rounded-xl px-3 py-2 border transition {{ $item->id === $lesson->id ? 'border-emerald-200 bg-emerald-50 text-slate-900 font-semibold' : 'border-transparent text-slate-600 hover:bg-white' }} {{ ($lessonValidationMap[$item->id] ?? false) === true ? 'text-emerald-700' : '' }}">
                                            @if(($lessonValidationMap[$item->id] ?? false) === true)
                                                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold mr-2">✓</span>
                                            @elseif($item->id === $lesson->id)
                                                <span class="inline-block mr-2">▶</span>
                                            @endif
                                            {{ $item->title }}
                                        </a>
                                    @endforeach
                                    @if($m->quizzes->isNotEmpty())
                                        <a href="{{ route('apprenant.quiz.take', ['moduleId' => $m->id, 'id' => $m->quizzes->first()->id]) }}" class="block px-3 py-2 text-sm font-semibold text-slate-900 hover:bg-white rounded-xl border border-transparent hover:border-slate-200">📝 Quiz de validation</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($course->finalQuizzes->isNotEmpty())
                            <a href="{{ route('apprenant.quiz.course.take', ['courseId' => $course->id, 'id' => $course->finalQuizzes->first()->id]) }}" class="block rounded-2xl border border-slate-200 bg-white/70 hover:bg-white px-4 py-3 text-sm font-semibold text-slate-900 transition">
                                🧪 Évaluation finale du cours
                            </a>
                        @endif

                        <div class="pt-1">
                            @if($certificateAvailable && !empty($certificatePdfPath))
                                <a href="{{ asset($certificatePdfPath) }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                                    🏅 Voir mon certificat
                                </a>
                            @else
                                <button type="button" onclick="showCertificateUnavailableToast()" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-slate-300 bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">
                                    Certificat indisponible
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>

            <main class="min-w-0">
                <!-- Header avec info leçon -->
                <div class="glass-card rounded-3xl p-8 mb-6" style="position: relative;">
                    <div class="min-w-0 pr-0 lg:pr-64">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Chapitre</div>
                            <h2 class="font-display text-4xl xl:text-[3.25rem] text-slate-900 mb-3 leading-tight">{{ $lesson->title }}</h2>
                            <p class="text-slate-600 mb-4 text-lg">{{ $lesson->description ?? 'Apprenez cet important sujet.' }}</p>
                            
                            <!-- Metadata -->
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span>📚</span>
                                    <span>Section : <strong>{{ $module->title }}</strong></span>
                                </div>
                                @if($lesson->duration_minutes)
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <span>⏱️</span>
                                        <span>{{ $lesson->duration_minutes }} min de lecture</span>
                                    </div>
                                @endif
                            </div>
                    </div>

                    <div class="flex flex-col items-end gap-3" style="position: absolute; top: 2rem; right: 2rem;">
                        @if($lessonValidated)
                            <span class="self-end px-5 py-3 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold border border-emerald-200 text-center whitespace-nowrap">✓ Chapitre validé</span>
                        @else
                            <button wire:click="validateCurrentLesson" class="inline-flex items-center justify-center px-5 py-3 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition shadow-sm whitespace-nowrap">Valider ce chapitre</button>
                        @endif

                        <a href="{{ route('apprenant.courses.catalogue') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 border border-slate-300 rounded-full text-sm font-semibold text-slate-700 transition whitespace-nowrap">
                            ← Retour au catalogue
                        </a>
                    </div>
                </div>

                @if($paymentMessage)
                    <div class="mb-6 p-4 bg-blue-100/80 border border-blue-300 rounded-2xl text-blue-700 text-sm">
                        ℹ️ {{ $paymentMessage }}
                    </div>
                @endif

                @if($paymentRequired && ! $canAccess)
                    <div class="rounded-3xl border border-slate-200 bg-white/80 p-8">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Section payante</div>
                        <h3 class="font-display text-2xl mt-2 text-slate-900">Accès bloqué</h3>
                        <p class="text-slate-600 mt-2">Cette section est payante. Effectuez le paiement pour accéder au contenu.</p>
                        <div class="mt-4 flex items-center gap-3">
                            <span class="chip px-4 py-2 rounded-full text-xs">Tarif : {{ number_format($module->price ?? 0, 0, ',', ' ') }} XOF</span>
                            <button wire:click="payModule" class="px-5 py-2 rounded-full text-white font-semibold" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Payer la section</button>
                        </div>
                    </div>
                @else
                    <!-- Contenu leçon -->
                    <section class="rounded-3xl border border-slate-200 bg-white/80 p-8 mb-6">
                        <div class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-4">Contenu du chapitre</div>
                        @if($lesson->video_url)
                            <div wire:ignore class="w-full max-w-full overflow-hidden rounded-2xl border border-slate-200 bg-black mb-6">
                                @if(Str::contains($lesson->video_url, ['youtube.com/embed/', 'player.vimeo.com/video/']))
                                    <div class="js-player w-full" data-plyr-provider="youtube" data-plyr-embed-id="{{ Str::afterLast($lesson->video_url, '/') }}"></div>
                                @else
                                    <video class="js-player w-full" playsinline controls>
                                        <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    </video>
                                @endif
                            </div>
                        @endif

                        <div class="rounded-2xl border border-slate-200 bg-white/70 p-4 mb-5">
                            <div class="mb-3">
                                <div class="text-sm font-semibold text-slate-900">Lecteur style</div>
                                <div class="text-xs text-slate-500">Barre latérale d’outils : sélectionnez un texte puis appliquez un surlignage. Les styles sont sauvegardés pour votre compte.</div>
                            </div>

                            <div class="lesson-style-workspace">
                                <aside class="lesson-style-toolbar" id="lesson-style-toolbar" aria-label="Outils d'édition">
                                    <div class="lesson-style-toolbar-group">
                                        <button type="button" title="Surligner jaune" class="lesson-style-toolbar-btn" data-command="hiliteColor" data-value="#fef08a">🟡</button>
                                        <button type="button" title="Surligner vert" class="lesson-style-toolbar-btn" data-command="hiliteColor" data-value="#bbf7d0">🟢</button>
                                        <button type="button" title="Surligner bleu" class="lesson-style-toolbar-btn" data-command="hiliteColor" data-value="#bfdbfe">🔵</button>
                                        <button type="button" title="Surligner rose" class="lesson-style-toolbar-btn" data-command="hiliteColor" data-value="#fbcfe8">🩷</button>
                                    </div>

                                    <hr class="my-2 border-slate-200">

                                    <div class="lesson-style-toolbar-group">
                                        <button type="button" title="Activer/Désactiver la gomme (clic sur le surlignage)" class="lesson-style-toolbar-btn" data-action="toggleEraser">🩹</button>
                                    </div>
                                </aside>

                                <div>
                                    <div class="lesson-style-status mb-2" id="lesson-style-status">Sélectionnez du texte puis appliquez une couleur de surlignage.</div>
                                    <div id="lesson-style-reader" contenteditable="true" spellcheck="false" class="prose prose-sm max-w-none text-slate-700 leading-relaxed trix-content lesson-style-reader">{!! $styledLessonContent !!}</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Ressources complémentaires -->
                    @if($lesson->resources->isNotEmpty())
                        <section class="mb-6">
                            <div class="glass-card rounded-3xl p-6">
                                <h3 class="font-display text-lg text-slate-900 mb-4">📎 Ressources complémentaires</h3>
                                <div class="grid gap-3 md:grid-cols-2">
                                    @forelse($lesson->resources as $resource)
                                        <div class="rounded-2xl bg-white/70 border border-slate-200 p-4 hover:border-slate-300 transition">
                                            @if($resource->type === 'video')
                                                <div wire:ignore>
                                                    <video class="js-player w-full rounded-lg" playsinline controls>
                                                        <source src="{{ $resource->url }}" type="video/mp4">
                                                    </video>
                                                </div>
                                            @else
                                                <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-slate-900 font-semibold hover:text-blue-600 transition">
                                                    📥 Télécharger ressource
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            @endif
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </section>
                    @endif

                    <!-- Navigation Précédent/Suivant -->
                    <div class="glass-card rounded-3xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                        @php
                            $allLessons = $course->modules->pluck('lessons')->flatten()->sortBy('order');
                            $lessonIds = $allLessons->pluck('id')->values();
                            $currentIndex = $lessonIds->search($lesson->id);
                            $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                            $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                        @endphp

                        @if($prevLesson)
                            <a href="{{ route('apprenant.lessons.show', $prevLesson->id) }}" class="inline-flex items-center justify-center min-w-[180px] gap-2 px-5 py-3 rounded-full border border-slate-300 text-slate-700 hover:bg-white transition font-semibold">
                                ← Chapitre précédent
                            </a>
                        @else
                            <div></div>
                        @endif

                        <div class="text-sm text-slate-600 text-center">
                            Chapitre <strong>{{ $currentIndex + 1 }}</strong> / <strong>{{ $allLessons->count() }}</strong>
                        </div>

                        @if($nextLesson)
                            <a href="{{ route('apprenant.lessons.show', $nextLesson->id) }}" class="inline-flex items-center justify-center min-w-[180px] gap-2 px-5 py-3 rounded-full bg-slate-900 text-white hover:bg-slate-800 transition font-semibold">
                                Chapitre suivant →
                            </a>
                        @else
                            <div class="px-5 py-2 rounded-full bg-emerald-100/80 text-emerald-700 text-sm font-semibold">
                                ✓ Cours terminé
                            </div>
                        @endif
                    </div>

                    <div class="glass-card rounded-3xl p-6 mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        @if($lessonValidated)
                            <span class="px-5 py-3 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold border border-emerald-200 text-center">✓ Chapitre validé</span>
                        @else
                            <button wire:click="validateCurrentLesson" class="inline-flex items-center justify-center min-w-[220px] px-5 py-3 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition shadow-sm">Valider ce chapitre</button>
                        @endif

                        <a href="{{ route('apprenant.courses.catalogue') }}" class="inline-flex items-center justify-center gap-2 min-w-[220px] px-5 py-3 bg-white hover:bg-slate-50 border border-slate-300 rounded-full text-sm font-semibold text-slate-700 transition">
                            ← Retour au catalogue
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

<script>
    (() => {
        const bindStyleReader = () => {
            const reader = document.getElementById('lesson-style-reader');
            const toolbar = document.getElementById('lesson-style-toolbar');
            const status = document.getElementById('lesson-style-status');
            if (!reader || !toolbar || !status || reader.dataset.bound === '1') return;

            reader.dataset.bound = '1';

            let savedRange = null;
            let eraserMode = false;
            let persistTimer = null;

            const getLivewireComponent = () => {
                const root = reader.closest('[wire\\:id]');
                if (!root || !window.Livewire) return null;
                return window.Livewire.find(root.getAttribute('wire:id'));
            };

            const persistStyledContent = () => {
                const component = getLivewireComponent();
                if (!component) return;
                component.call('saveStyledContent', reader.innerHTML);
            };

            const queuePersistStyledContent = () => {
                if (persistTimer) {
                    clearTimeout(persistTimer);
                }
                persistTimer = setTimeout(() => {
                    persistStyledContent();
                }, 450);
            };

            const saveSelection = () => {
                const selection = window.getSelection();
                if (!selection || selection.rangeCount === 0) return;
                const range = selection.getRangeAt(0);
                const container = range.commonAncestorContainer.nodeType === Node.TEXT_NODE
                    ? range.commonAncestorContainer.parentNode
                    : range.commonAncestorContainer;
                if (!reader.contains(container)) return;
                savedRange = range.cloneRange();
            };

            const restoreSelection = () => {
                if (!savedRange) return false;
                const selection = window.getSelection();
                if (!selection) return false;
                selection.removeAllRanges();
                selection.addRange(savedRange);
                return true;
            };

            const hasTextSelection = () => {
                const selection = window.getSelection();
                return !!selection && selection.rangeCount > 0 && selection.toString().trim().length > 0;
            };

            const updateToolbarState = () => {
                if (eraserMode) {
                    status.textContent = 'Mode gomme actif : cliquez sur un texte surligné pour effacer le surlignage.';
                    return;
                }

                status.textContent = hasTextSelection()
                    ? 'Sélection active : choisissez une couleur de surlignage.'
                    : 'Sélectionnez du texte puis appliquez une couleur.';
            };

            const unwrapElement = (element) => {
                const parent = element.parentNode;
                if (!parent) return;
                while (element.firstChild) {
                    parent.insertBefore(element.firstChild, element);
                }
                parent.removeChild(element);
            };

            const cleanupElementIfNeeded = (element) => {
                if (!element || element === reader) return;

                const tag = element.tagName;
                if (!['SPAN', 'FONT', 'MARK'].includes(tag)) return;

                if (tag === 'MARK') {
                    unwrapElement(element);
                    return;
                }

                if (element.style.length === 0 && element.attributes.length === 0) {
                    unwrapElement(element);
                }
            };

            const clearHighlightFromElement = (element) => {
                if (!element) return false;

                if (element.tagName === 'MARK') {
                    unwrapElement(element);
                    return true;
                }

                const hasBackground = !!element.style.backgroundColor || !!element.style.background;
                if (!hasBackground) return false;

                element.style.backgroundColor = '';
                element.style.background = '';

                if (element.getAttribute('style') === '') {
                    element.removeAttribute('style');
                }

                cleanupElementIfNeeded(element);
                return true;
            };

            const eraseHighlightAtClick = (target) => {
                let node = target;
                if (!node) return false;

                if (node.nodeType === Node.TEXT_NODE) {
                    node = node.parentElement;
                }

                if (!(node instanceof Element) || !reader.contains(node)) return false;

                let current = node;
                while (current && current !== reader) {
                    if (clearHighlightFromElement(current)) {
                        return true;
                    }
                    current = current.parentElement;
                }

                return false;
            };

            const setEraserMode = (active) => {
                eraserMode = active;
                reader.classList.toggle('is-eraser', eraserMode);

                const eraserButton = toolbar.querySelector('[data-action="toggleEraser"]');
                if (eraserButton) {
                    eraserButton.classList.toggle('is-active', eraserMode);
                }

                updateToolbarState();
            };

            const applyCommand = (command, value = null) => {
                reader.focus();
                restoreSelection();
                document.execCommand(command, false, value);
                saveSelection();
                updateToolbarState();
                queuePersistStyledContent();
            };

            toolbar.addEventListener('mousedown', (event) => {
                if (event.target.closest('button, select')) {
                    event.preventDefault();
                }
            });

            toolbar.addEventListener('click', (event) => {
                const button = event.target.closest('button');
                if (!button) return;

                const command = button.dataset.command;
                const value = button.dataset.value;
                const action = button.dataset.action;

                if (command) {
                    setEraserMode(false);
                    applyCommand(command, value ?? null);
                    return;
                }

                if (action === 'toggleEraser') {
                    setEraserMode(!eraserMode);
                }
            });

            reader.addEventListener('beforeinput', (event) => {
                const allowed = ['historyUndo', 'historyRedo'];
                if (!allowed.includes(event.inputType)) {
                    event.preventDefault();
                }
            });

            reader.addEventListener('keydown', (event) => {
                const allowedKeys = ['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Shift', 'Control', 'Alt', 'Meta', 'Tab', 'Escape', 'Home', 'End', 'PageUp', 'PageDown'];
                if (allowedKeys.includes(event.key)) return;

                if ((event.ctrlKey || event.metaKey) && ['a', 'c', 'z', 'y'].includes(event.key.toLowerCase())) {
                    return;
                }

                event.preventDefault();
            });

            reader.addEventListener('mouseup', () => {
                saveSelection();
                updateToolbarState();
            });

            reader.addEventListener('click', (event) => {
                if (!eraserMode) return;

                event.preventDefault();
                const removed = eraseHighlightAtClick(event.target);
                status.textContent = removed
                    ? 'Surlignage effacé à cet endroit.'
                    : 'Aucun surlignage détecté à cet endroit.';

                if (removed) {
                    queuePersistStyledContent();
                }
            });

            reader.addEventListener('keyup', () => {
                saveSelection();
                updateToolbarState();
            });

            document.addEventListener('selectionchange', () => {
                saveSelection();
                updateToolbarState();
            });

            reader.addEventListener('paste', (event) => event.preventDefault());
            reader.addEventListener('drop', (event) => event.preventDefault());

            updateToolbarState();
        };

        window.showCertificateUnavailableToast = () => {
            const existing = document.getElementById('certificate-unavailable-toast');
            if (existing) {
                existing.remove();
            }

            const toast = document.createElement('div');
            toast.id = 'certificate-unavailable-toast';
            toast.textContent = 'Certificat indisponible pour le moment.';
            toast.style.position = 'fixed';
            toast.style.top = '18px';
            toast.style.right = '18px';
            toast.style.zIndex = '9999';
            toast.style.padding = '13px 18px';
            toast.style.borderRadius = '9999px';
            toast.style.border = '1px solid rgb(203 213 225)';
            toast.style.background = 'rgb(248 250 252)';
            toast.style.color = 'rgb(71 85 105)';
            toast.style.fontSize = '14px';
            toast.style.fontWeight = '600';
            toast.style.boxShadow = '0 14px 28px rgba(15, 23, 42, 0.18)';
            toast.style.transform = 'translateY(-8px)';
            toast.style.opacity = '0';
            toast.style.transition = 'all .18s ease';

            document.body.appendChild(toast);

            requestAnimationFrame(() => {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            });

            setTimeout(() => {
                toast.style.transform = 'translateY(-8px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 220);
            }, 1900);
        };

        document.addEventListener('DOMContentLoaded', bindStyleReader);
        document.addEventListener('livewire:navigated', bindStyleReader);
    })();
</script>
