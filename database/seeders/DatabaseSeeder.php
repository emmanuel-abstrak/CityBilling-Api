<?php

namespace Database\Seeders;

use App\Library\Enums\Gender;
use App\Library\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Repositories\Currencies\CurrencyRepository;
use App\Repositories\Suburbs\SuburbRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        (new CurrencyRepository())->create([
            'code' => 'USD',
            'symbol' => '$'
        ]);
        (new CurrencyRepository())->create([
            'code' => 'ZIG',
            'symbol' => 'Z$'
        ]);

        $suburbs = [
            'Mucheke',
            'Rujeko',
            'Rhodene',
            'Target Kopje',
            'Eastvale'
        ];

        foreach($suburbs as $suburb) {
            (new SuburbRepository())->create(['name' => $suburb]);
        }

        (new UserRepository())->create([
            'first_name' => 'Emmanuel',
            'last_name' => 'Mahaso',
            'email' => 'emmanuel@abstrak.agency',
            'password' => Hash::make('password'),
            'phone_number' => '0714683811',
            'role' => UserRole::admin->value,
            'gender' => Gender::male->value
        ]);
    }
}
