<?php

// File: update_policies.php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Str;

// Directory containing policies
$policyDir = __DIR__ . '/app/Policies';
$files = glob($policyDir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);

    // Replace 'User $user' with 'User|TenantUser $user' in method signatures
    $newContent = preg_replace(
        '/\b(User)\s+\$user\b/',
        'User|TenantUser $user',
        $content
    );

    // Add TenantUser import if not already present
    if (!Str::contains($newContent, 'use App\Models\TenantUser;')) {
        $newContent = Str::replaceFirst(
            "use App\Models\User;\n",
            "use App\Models\User;\nuse App\Models\TenantUser;\n",
            $newContent
        );
    }

    // Write the updated content back to the file
    file_put_contents($file, $newContent);
    echo "Updated: $file\n";
}

echo "All policies updated!\n";
