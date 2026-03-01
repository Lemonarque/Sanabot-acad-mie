<div class="min-h-screen aurora-bg py-10">
    <div class="max-w-3xl mx-auto px-6">
        <div class="glass-card rounded-3xl p-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-slate-500">Quiz</div>
                    <h2 class="font-display text-3xl">{{ $quiz->title }}</h2>
                </div>
                <a href="{{ route('apprenant.dashboard') }}" class="chip px-4 py-2 rounded-full text-sm">Retour</a>
            </div>

            @if($message)
                <div class="mb-4 text-emerald-600 font-semibold">{{ $message }}</div>
            @endif

            @if($submitted)
                <div class="mb-6 text-center">
                    <span class="text-lg font-semibold text-slate-900">Score : {{ $score }} / {{ $quiz->questions->count() }}</span>
                </div>
            @else
                <form wire:submit.prevent="submit" class="space-y-8">
                    @foreach($quiz->questions as $question)
                        <div class="rounded-2xl bg-white/70 border border-slate-200 p-5">
                            <div class="font-semibold text-slate-900 mb-3">{{ $loop->iteration }}. {{ $question->content }}</div>
                            <div class="space-y-2">
                                @foreach($question->answers as $answer)
                                    <label class="flex items-center space-x-2 text-slate-700">
                                        <input type="checkbox" wire:model="answers.{{ $question->id }}" value="{{ $answer->id }}" class="rounded border-slate-300 text-slate-900 focus:ring-sky-300" />
                                        <span>{{ $answer->content }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="w-full py-3 px-4 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition">Valider mes réponses</button>
                </form>
            @endif
        </div>
    </div>
</div>
