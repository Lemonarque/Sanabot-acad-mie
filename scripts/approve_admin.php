<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
if ($adminRoleId) {
    DB::table('users')
        ->where('role_id', $adminRoleId)
        ->update([
            'approval_status' => 'approved',
            'approved_at' => Carbon::now(),
            'approved_by' => null,
        ]);
}

fwrite(STDOUT, "Admin approved\n");
