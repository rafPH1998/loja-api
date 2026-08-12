<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@loja.com'],
            [
                'name' => 'Administrador',
                'password' => 'password',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'cliente@loja.com'],
            [
                'name' => 'Cliente Loja',
                'password' => 'password',
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        Address::query()->updateOrCreate(
            [
                'user_id' => $admin->id,
                'zipcode' => '40020000',
            ],
            [
                'street' => 'Rua Chile',
                'number' => '10',
                'city' => 'Salvador',
                'state' => 'BA',
                'country' => 'Brasil',
                'complement' => null,
            ]
        );

        Address::query()->updateOrCreate(
            [
                'user_id' => $customer->id,
                'zipcode' => '01310100',
            ],
            [
                'street' => 'Avenida Paulista',
                'number' => '1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'country' => 'Brasil',
                'complement' => 'Apto 12',
            ]
        );

        $this->command?->info('Admin: admin@loja.com / password');
        $this->command?->info('Cliente: cliente@loja.com / password');
    }
}
