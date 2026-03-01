<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use Livewire\Component;

class CertificatesManage extends Component
{
    public $certificates;
    public $search = '';

    public function mount()
    {
        $this->loadCertificates();
    }

    public function loadCertificates()
    {
        $query = Certificate::query()->with(['enrollment.user', 'enrollment.course'])->latest();

        if ($this->search) {
            $query->whereHas('enrollment.user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhereHas('enrollment.course', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }

        $this->certificates = $query->get();
    }

    public function searchCertificates()
    {
        $this->loadCertificates();
    }

    public function revoke($id)
    {
        Certificate::findOrFail($id)->delete();
        $this->loadCertificates();
    }

    public function render()
    {
        return view('components.admin.certificates-manage');
    }
}
