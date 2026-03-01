<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-8">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Formateur</div>
                <h2 class="font-display text-4xl">{{ $lessonId ? 'Editer le chapitre' : 'Nouveau chapitre' }}</h2>
                <p class="text-slate-600 mt-2">Section : {{ $module->title }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('lessons.manage', ['moduleId' => $module->id]) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 hover:bg-white border border-slate-200 rounded-full text-sm font-medium text-slate-700 transition shadow-sm">
                    ← Retour aux chapitres
                </a>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-display text-2xl">{{ $lessonId ? 'Modifier les informations' : 'Ajouter un chapitre' }}</h3>
                    <p class="text-sm text-slate-600 mt-1">{{ $lessonId ? 'Mettez a jour la video et le contenu.' : 'Creez un nouveau chapitre avec video et contenu.' }}</p>
                </div>
                <span class="chip px-3 py-1 rounded-full text-xs">🎥 Video + 📝 Texte</span>
            </div>

            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Titre du chapitre *</label>
                    <input
                        type="text"
                        wire:model.defer="title"
                        class="block w-full border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-sky-200 focus:border-sky-300"
                        placeholder="Ex: Introduction aux bases"
                        required
                    >
                    @error('title') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Video (URL)</label>
                        <input
                            type="url"
                            wire:model.defer="video_url"
                            class="block w-full border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-sky-200 focus:border-sky-300"
                            placeholder="https://youtube.com/..."
                        />
                        <p class="text-xs text-slate-500 mt-1">YouTube, Vimeo ou lien direct</p>
                        @error('video_url') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Ou telecharger</label>
                        <input
                            type="file"
                            wire:model="video_file"
                            accept="video/mp4,video/webm,video/ogg"
                            class="block w-full text-sm text-slate-600 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:text-white file:bg-slate-900 hover:file:bg-slate-800"
                        />
                        <p class="text-xs text-slate-500 mt-1">MP4/WebM/OGG • Max 500 Mo</p>
                        @error('video_file') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Contenu du chapitre *</label>
                    <input id="lesson-content" type="hidden" wire:model.defer="content" value="{{ $content }}">
                    <textarea
                        id="lesson-content-editor"
                        wire:ignore
                        class="rounded-xl bg-white w-full border-slate-200"
                    ></textarea>
                    @error('content') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Free Preview Option -->
                <div class="border-t border-slate-200 pt-4 mt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            wire:model="isFreePrev"
                            class="rounded border-slate-200"
                        >
                        <span class="text-sm font-semibold text-slate-900">🎁 Accessible gratuitement (démo/aperçu)</span>
                    </label>
                    <p class="text-xs text-slate-500 mt-2 ml-6">Les apprenants non inscrits au cours pourront voir ce chapitre gratuitement</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-slate-900 text-white rounded-full font-semibold hover:bg-slate-800 transition shadow-sm"
                    >
                        {{ $lessonId ? '✓ Mettre a jour' : '+ Ajouter le chapitre' }}
                    </button>
                    <button
                        type="button"
                        wire:click="cancel"
                        class="px-6 py-2.5 bg-slate-200 text-slate-700 rounded-full font-semibold hover:bg-slate-300 transition"
                    >
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hiddenInput = document.getElementById('lesson-content');
        const initialContent = hiddenInput ? hiddenInput.value : '';

        const existing = tinymce.get('lesson-content-editor');
        if (existing) {
            existing.remove();
        }

        tinymce.init({
            selector: '#lesson-content-editor',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            setup: function(editor) {
                editor.on('change keyup paste', function() {
                    const content = editor.getContent();
                    if (hiddenInput) {
                        hiddenInput.value = content;
                    }
                    @this.set('content', content, false);
                });
            },
            init_instance_callback: function(editor) {
                editor.setContent(initialContent || '');
                const hiddenInput = document.getElementById('lesson-content');
                if (hiddenInput) {
                    hiddenInput.value = initialContent || '';
                }
            }
        });
    });
</script>
@endpush
