<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function getAll(array $filters= []): LengthAwarePaginator
    {
        $perPage = $filter['per_page'] ?? 10;

        return User::with('roles')
            ->when(isset($filters['role']), fn($q)=>
                $q->whereHas('roles', fn($r)=>
                    $r->where('name',$filters['roler'])
                )
            )
            ->when(isset($filters['search']), fn($q) =>
                $q->where(function ($s) use ($filters) {
                    $s->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('email', 'like', "%{$filters['search']}%");
                })
            )
            ->latest()
            ->paginate($perPage);
    }

    public function getById(User $user): User
    {
        return $user->load('roles');
    }

    public function create(array $data): User
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),

        ]);

        $user->assignRole($data['role']);

        return $user->load('roles');
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['name']))  {
        $user->name = $data['name'];
    }

    if (isset($data['email'])) {
        $user->email = $data['email'];
    }

    if (isset($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    $user->save();

    if (isset($data['role'])) {
        $user->syncRoles([$data['role']]);
    }

    return $user->load('roles');
    }
}