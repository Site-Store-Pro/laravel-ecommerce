<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
$users = User::all();
foreach ($users as $user) {
    echo "User: {$user->name}, Email: {$user->email}, Verified At: " . ($user->email_verified_at ? $user->email_verified_at : 'NULL') . ", Role ID: " . (is_object($user->role_id) ? $user->role_id->name . ' (' . $user->role_id->value . ')' : $user->role_id) . ", isAdmin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
}
