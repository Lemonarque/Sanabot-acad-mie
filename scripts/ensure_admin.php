<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$adminEmail = env('ADMIN_EMAIL', 'admin@sanabot.com');
$adminPassword = env('ADMIN_PASSWORD', 'admin1234');

$adminRole = Role::updateOrCreate(['name' => 'admin']);
Role::updateOrCreate(['name' => 'formateur']);
Role::updateOrCreate(['name' => 'apprenant']);
Role::updateOrCreate(['name' => 'institution']);

User::updateOrCreate(
    ['email' => $adminEmail],
    [
        'name' => 'Admin Principal',
        'password' => Hash::make($adminPassword),
        'role_id' => $adminRole->id,
        'admin_level' => 'super_admin',
        'approval_status' => 'approved',
    ]
);

fwrite(STDOUT, "Admin ensured: {$adminEmail}" . PHP_EOL);
