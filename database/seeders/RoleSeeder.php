<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name' => 'Super Admin','guard_name'=>'web'],
            ['name' => 'Admin','guard_name'=>'web'],
            ['name' => 'Employe','guard_name'=>'web'],
        ]);
    }
}
