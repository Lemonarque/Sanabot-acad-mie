<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;

class PaymentsManage extends Component
{
    public $payments;
    public $search = '';

    public function mount()
    {
        $this->loadPayments();
    }

    public function loadPayments()
    {
        $query = Payment::query()->with(['user', 'course', 'module.course'])->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function ($subq) {
                    $subq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('course', function ($subq) {
                    $subq->where('title', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('module.course', function ($subq) {
                    $subq->where('title', 'like', '%' . $this->search . '%');
                })
                ->orWhere('transaction_id', 'like', '%' . $this->search . '%');
            });
        }

        $this->payments = $query->get();
    }

    public function searchPayments()
    {
        $this->loadPayments();
    }

    public function render()
    {
        return view('components.admin.payments-manage');
    }
}
