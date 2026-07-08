<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Crée (ou met à jour) les 3 comptes utilisateurs de l'application.
     *
     * Le mot de passe est hashé automatiquement grâce au cast
     * 'password' => 'hashed' défini dans App\Models\User,
     * donc on peut lui passer la valeur en clair directement.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Rosnel',
                'email'    => 'rosnel@mundotech.com',
                'password' => 'Rosnelled237@',
            ],
            [
                'name'     => 'Steve',
                'email'    => 'steve@mundotech.com',
                'password' => 'varrel237',
            ],
            [
                'name'     => 'Oscar',
                'email'    => 'oscar@mundotech.com',
                'password' => 'oscar237',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name'     => $user['name'],
                    'password' => $user['password'],
                ]
            );
        }
    }
}
