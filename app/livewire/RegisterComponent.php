<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class RegisterComponent extends Component
{
    public function render()
    {
        return view('livewire.register-component')
        ->layout('layouts.app');
    }

    public $name, $email, $password, $password_confirmation, $phone;
    public $phone_visible = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'phone_visible' => 'boolean'
    ];
    
    public function register()
    {
        $this->validate();
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'phone_visible' => $this->phone_visible,
            'balance' => 0
        ]);
        
        return redirect()->route('login')
            ->with('success', 'Uspešno ste se registrovali! Možete se ulogovati.');
    }
}
