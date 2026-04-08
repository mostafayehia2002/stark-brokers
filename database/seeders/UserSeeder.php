<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       // User::factory()->count(15)->create();

       $user=User::create([
            'full_name'=>'Admin',
            'username' => 'admin',
            'phone'=>'+201226717838',
            'email' => 'admin@gmail.com',
            'password'=>Hash::make('12345678'),
            'type'=>UserType::ADMIN
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
