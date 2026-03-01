<div class="glass-card rounded-2xl p-3 mb-6">
    <div class="flex flex-wrap gap-2">
        @php
            $activeTab = $active ?? null;
            $isOverview = $activeTab ? $activeTab === 'dashboard' : request()->routeIs('institution.dashboard');
            $isLearners = $activeTab ? $activeTab === 'learners' : request()->routeIs('institution.learners');
            $isCourseRequests = $activeTab ? $activeTab === 'course-requests' : request()->routeIs('institution.course.requests');
            $isReporting = $activeTab ? $activeTab === 'reporting' : request()->routeIs('institution.reporting');
        @endphp

        @if($isOverview)
            <a href="{{ route('institution.dashboard') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-white" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Vue d'ensemble</a>
        @else
            <a href="{{ route('institution.dashboard') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-slate-700 bg-white/70">Vue d'ensemble</a>
        @endif

        @if($isLearners)
            <a href="{{ route('institution.learners') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-white" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Apprenants</a>
        @else
            <a href="{{ route('institution.learners') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-slate-700 bg-white/70">Apprenants</a>
        @endif

        @if($isCourseRequests)
            <a href="{{ route('institution.course.requests') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-white" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Demandes cours</a>
        @else
            <a href="{{ route('institution.course.requests') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-slate-700 bg-white/70">Demandes cours</a>
        @endif

        @if($isReporting)
            <a href="{{ route('institution.reporting') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-white" style="background: linear-gradient(135deg, var(--teal), var(--mint));">Reporting</a>
        @else
            <a href="{{ route('institution.reporting') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-slate-700 bg-white/70">Reporting</a>
        @endif
    </div>
</div>
