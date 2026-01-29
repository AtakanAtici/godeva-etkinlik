<?php

namespace App\Livewire;

use Livewire\Component;

class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public string $error = '';

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        // Statik kullanıcı doğrulama
        if ($this->username === 'godeva' && $this->password === 'G5s2eb9L') {
            session(['authenticated' => true]);
            return redirect()->intended('/host');
        } else {
            $this->error = 'Kullanıcı adı veya şifre hatalı!';
            $this->password = '';
        }
    }

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.guest');
    }
}
