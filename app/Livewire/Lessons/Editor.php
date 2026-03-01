<?php

namespace App\Livewire\Lessons;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Editor extends Component
{
    use WithFileUploads;

    public $module;
    public $lessonId = null;
    public $title = '';
    public $content = '';
    public $video_url = '';
    public $video_file = null;
    public $isFreePrev = false;

    public function mount($moduleId = null, $lessonId = null)
    {
        if ($lessonId) {
            $lesson = Lesson::with('module')->findOrFail($lessonId);
            $this->module = $lesson->module;
            $this->lessonId = $lesson->id;
            $this->title = $lesson->title;
            $this->content = $lesson->content;
            $this->video_url = $lesson->video_url;
            $this->isFreePrev = $lesson->is_free_preview ?? false;
            return;
        }

        $this->module = Module::findOrFail($moduleId);
    }

    protected function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:512000',
        ];
    }

    protected function normalizeVideoUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $trimmed = trim($url);
        if ($trimmed === '') {
            return null;
        }

        if (preg_match('~^(https?://)?(www\.)?(youtube\.com|youtu\.be)/~i', $trimmed)) {
            $id = null;
            if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~i', $trimmed, $m)) {
                $id = $m[1];
            } elseif (preg_match('~v=([A-Za-z0-9_-]{6,})~i', $trimmed, $m)) {
                $id = $m[1];
            } elseif (preg_match('~embed/([A-Za-z0-9_-]{6,})~i', $trimmed, $m)) {
                $id = $m[1];
            }

            if ($id) {
                return 'https://www.youtube.com/embed/' . $id;
            }
        }

        if (preg_match('~^(https?://)?(www\.)?vimeo\.com/~i', $trimmed)) {
            if (preg_match('~vimeo\.com/(\d+)~', $trimmed, $m)) {
                return 'https://player.vimeo.com/video/' . $m[1];
            }
            if (preg_match('~player\.vimeo\.com/video/(\d+)~', $trimmed, $m)) {
                return 'https://player.vimeo.com/video/' . $m[1];
            }
        }

        return $trimmed;
    }

    protected function storeVideoIfNeeded(): ?string
    {
        if (! $this->video_file) {
            return null;
        }

        $path = $this->video_file->store('videos', 'public');
        return Storage::disk('public')->url($path);
    }

    public function save()
    {
        $this->validate();

        $videoUrl = $this->storeVideoIfNeeded() ?? $this->normalizeVideoUrl($this->video_url);

        if ($this->lessonId) {
            $lesson = Lesson::findOrFail($this->lessonId);
            $lesson->update([
                'title' => $this->title,
                'content' => $this->content,
                'video_url' => $videoUrl,
                'is_free_preview' => $this->isFreePrev,
            ]);
        } else {
            $this->module->lessons()->create([
                'title' => $this->title,
                'content' => $this->content,
                'video_url' => $videoUrl,
                'is_free_preview' => $this->isFreePrev,
            ]);
        }

        return redirect()->route('lessons.manage', ['moduleId' => $this->module->id]);
    }

    public function cancel()
    {
        return redirect()->route('lessons.manage', ['moduleId' => $this->module->id]);
    }

    public function render()
    {
        return view('components.lessons.editor');
    }
}
