<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        $formateurRole = \App\Models\Role::firstOrCreate(['name' => 'formateur']);
        $apprenantRole = \App\Models\Role::firstOrCreate(['name' => 'apprenant']);
        $institutionRole = \App\Models\Role::firstOrCreate(['name' => 'institution']);

        \App\Models\User::firstOrCreate([
            'email' => 'admin@sanabot.com',
        ], [
            'name' => 'Admin Principal',
            'password' => bcrypt('admin1234'),
            'role_id' => $adminRole->id,
        ]);

        \App\Models\User::firstOrCreate([
            'email' => 'formateur@sanabot.com',
        ], [
            'name' => 'Formateur Expert',
            'password' => bcrypt('formateur1234'),
            'role_id' => $formateurRole->id,
        ]);

        \App\Models\User::firstOrCreate([
            'email' => 'apprenant@sanabot.com',
        ], [
            'name' => 'Apprenant Test',
            'password' => bcrypt('apprenant1234'),
            'role_id' => $apprenantRole->id,
        ]);

        $institutionUser = User::firstOrCreate([
            'email' => 'institution@sanabot.com',
        ], [
            'name' => 'Institution Demo',
            'password' => bcrypt('institution1234'),
            'role_id' => $institutionRole->id,
            'approval_status' => 'approved',
        ]);

        $institution = Institution::firstOrCreate([
            'owner_user_id' => $institutionUser->id,
        ], [
            'name' => 'Institution Demo',
            'slug' => Str::slug('Institution Demo') . '-demo',
            'contact_email' => $institutionUser->email,
            'approved_learner_quota' => 0,
            'is_active' => true,
        ]);

        if ($institutionUser->institution_id !== $institution->id) {
            $institutionUser->institution_id = $institution->id;
            $institutionUser->save();
        }
    }
}
