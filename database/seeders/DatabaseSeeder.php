<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Lance tous les seeders de l'application.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
