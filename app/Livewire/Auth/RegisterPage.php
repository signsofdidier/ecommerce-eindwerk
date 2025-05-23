<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Register')]
class RegisterPage extends Component
{

    public $name;
    public $email;
    public $password;
    public $company;

    public function mount()
    {
        $this->company = request()->route('company');
    }

    // register user
    public function save(){
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|max:255'
        ]);
        //save to database
        $user = User::create([
            'password' => Hash::make($this->password),
            'email' => $this->email,
            'name' => $this->name,
        ]);

        // login user
        auth()->login($user);

        // intended redirect naar de pagina waar je vandaan komt
        return redirect()->to(tenant_route('home'));



    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
