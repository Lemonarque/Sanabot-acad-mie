<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UsersManage extends Component
{
    public $users;
    public $search = '';
    public $status = 'all';
    public $editingId = null;
    public $name = '';
    public $email = '';
    public $role = '';
    public $adminLevel = '';

    public $profileModal = false;
    public $profileUser;

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $query = User::with('role')->latest();
        if ($this->status !== 'all') {
            $query->where('approval_status', $this->status);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }
        $this->users = $query->get();
    }

    public function searchUsers()
    {
        $this->loadUsers();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role?->name ?? '';
        $this->adminLevel = $user->admin_level ?? '';
    }

    public function update()
    {
        $user = User::findOrFail($this->editingId);
        $user->name = $this->name;
        $user->email = $this->email;
        if ($this->role) {
            $role = Role::where('name', $this->role)->first();
            $user->role_id = $role?->id;
            
            // Set admin_level only if role is admin
            if ($this->role === 'admin') {
                $user->admin_level = $this->adminLevel ?: 'admin';
            } else {
                $user->admin_level = null;
            }
        } else {
            $user->role_id = null;
            $user->admin_level = null;
        }
        $user->save();
        $this->reset(['editingId', 'name', 'email', 'role', 'adminLevel']);
        $this->loadUsers();
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'name', 'email', 'role', 'adminLevel']);
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'approved';
        $user->approved_at = now();
        $user->approved_by = Auth::id();
        $user->save();
        $this->loadUsers();
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'rejected';
        $user->approved_at = null;
        $user->approved_by = Auth::id();
        $user->save();
        $this->loadUsers();
    }

    public function viewProfile($id)
    {
        $this->profileUser = User::with('role')->findOrFail($id);
        $this->profileModal = true;
    }

    public function closeProfile()
    {
        $this->profileModal = false;
        $this->profileUser = null;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        $this->loadUsers();
     }

    public function render()
    {
        return view('components.admin.users-manage');
    }
}
