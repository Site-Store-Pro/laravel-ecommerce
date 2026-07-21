<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Get the admin user
$admin = \App\Models\User::where('email', 'admin@support.local')->first();
if (!$admin) {
    echo "Admin user not found!\n";
    exit(1);
}

echo "Admin user: {$admin->name}, Role: {$admin->role_id->name}, isAdmin: " . ($admin->isAdmin() ? 'YES' : 'NO') . "\n";
echo "Email verified: " . ($admin->email_verified_at ? 'YES' : 'NO') . "\n";
echo "\n";

// Check user role details
echo "role_id value: " . $admin->role_id->value . "\n";
echo "role_id name: " . $admin->role_id->name . "\n";
echo "isAdmin(): " . ($admin->isAdmin() ? 'true' : 'false') . "\n";
echo "isEcommerceAdmin(): " . ($admin->isEcommerceAdmin() ? 'true' : 'false') . "\n";
echo "isOrderProcessor(): " . ($admin->isOrderProcessor() ? 'true' : 'false') . "\n";
echo "isStaff(): " . ($admin->isStaff() ? 'true' : 'false') . "\n";
echo "\n";

// Check if UserRole::Admin is correct
echo "UserRole::Admin value: " . \App\Enums\UserRole::Admin->value . "\n";
echo "comparison: admin->role_id === UserRole::Admin: " . ($admin->role_id === \App\Enums\UserRole::Admin ? 'YES' : 'NO') . "\n";

// Actually simulate a request
echo "\n--- Simulating HTTP request ---\n";
$image = tmpfile();
$tmpName = stream_get_meta_data($image)['uri'];
// create a minimal JPEG
$imgData = "\xff\xd8\xff\xe0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00\xff\xdb\x00C\x00\x08\x06\x06\x07\x06\x05\x08\x07\x07\x07\t\t\x08\n\x0c\x14\r\x0c\x0b\x0b\x0c\x19\x12\x13\x0f\x14\x1d\x1a\x1f\x1e\x1d\x1a\x1c\x1c $.' \",#\x1c\x1c(7),01444\x1f'9=82<.342\x1e\x1c\x1c;44;;\xff\xc0\x00\x0b\x08\x00\x01\x00\x01\x01\x01\x11\x00\xff\xc4\x00\x1f\x00\x00\x01\x05\x01\x01\x01\x01\x01\x01\x00\x00\x00\x00\x00\x00\x00\x00\x01\x02\x03\x04\x05\x06\x07\x08\t\n\x0b\xff\xda\x00\x08\x01\x01\x00\x00?\x00\xfb\xff\xd9";
file_put_contents($tmpName, $imgData);

$uploadedFile = new \Illuminate\Http\UploadedFile($tmpName, 'test.jpg', 'image/jpeg', null, true);

$request = \Illuminate\Http\Request::create(
    '/admin/cms-pages/upload-image',
    'POST',
    [],
    [],
    ['file' => $uploadedFile],
    ['CONTENT_TYPE' => 'multipart/form-data']
);

// Authenticate the user on the request
$guard = \Illuminate\Support\Facades\Auth::guard('web');
$guard->setUser($admin);
\Illuminate\Support\Facades\Auth::shouldUse('web');

// Bind auth
$app->instance('auth', \Illuminate\Support\Facades\Auth::getFacadeRoot());

echo "Auth::check() after setting user: " . (\Illuminate\Support\Facades\Auth::check() ? 'YES' : 'NO') . "\n";
echo "Auth::user(): " . (\Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->email : 'null') . "\n";

// The problem might be that $request->user() is different from Auth::user() - let's check
// because the session isn't set in our test context, the request might not have the user
echo "\nrequest->user(): " . ($request->user() ? $request->user()->email : 'null') . "\n";

echo "\nConclusion: If request->user() is null while Auth::user() is set, the session cookies in the browser are not being sent with the XHR request.\n";
