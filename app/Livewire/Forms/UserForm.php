<?php

namespace App\Livewire\Forms;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?int $userId = null;

    #[Validate]
    public string $name = '';

    #[Validate]
    public string $email = '';

    #[Validate]
    public int $role_id = 1;

    public bool $opt_in = false;

    #[Validate]
    public string $password = '';

    #[Validate]
    public string $password_confirmation = '';

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
            'role_id'  => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
            'opt_in'   => ['boolean'],
            'password' => $this->userId
                ? ['nullable', 'string', Password::defaults(), 'confirmed']
                : ['required', 'string', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    public function fillFromUser(User $user): void
    {
        $this->userId  = $user->id;
        $this->name    = $user->name;
        $this->email   = $user->email;
        $this->role_id = is_object($user->role_id) ? $user->role_id->value : (int)$user->role_id;
        $this->opt_in  = (bool) $user->opt_in;
    }

    public function store(): User
    {
        $this->validate();

        return User::create([
            'name'              => $this->name,
            'email'             => $this->email,
            'role_id'           => $this->role_id,
            'opt_in'            => $this->opt_in,
            'password'          => Hash::make($this->password),
            'email_verified_at' => now(),
        ]);
    }

    public function update(User $user): void
    {
        $this->validate();

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
        ];

        $roleVal = is_object($user->role_id) ? $user->role_id->value : (int) $user->role_id;
        if (in_array($roleVal, [1, 2])) {
            $oldOptIn = (bool) $user->opt_in;
            $data['opt_in'] = $this->opt_in;
            if ($oldOptIn !== $this->opt_in) {
                \App\Services\OptinService::syncUserOptIn($user, $this->opt_in);
            }
        }

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->password              = '';
        $this->password_confirmation = '';
    }
}
