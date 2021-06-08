<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public $search = '';


    public function render()
    {
        $users = User::all();
        if (strlen($this->search) > 2) {
            $users = User::where('username', 'like', "%{$this->search}%")->get();
        }
        return view('livewire.users.user-index', [
            'users' => $users
        ])
                 ->layout('layouts.main');
    }
}
