<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\ExpectantMother;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use a secure, temporary default password (e.g., a random string)
        $temporaryPassword = 'TempPass2025!'; // Change this to a secure, unique password (e.g., Str::random(16) in Tinker)

        // Update all non-Bcrypt password_hash values to Bcrypt hashes
        ExpectantMother::whereNotNull('password_hash')
            ->where('password_hash', 'NOT LIKE', '$2y$%') // Check for non-Bcrypt hashes
            ->update(['password_hash' => bcrypt($temporaryPassword)]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is complex without original passwords, so we’ll skip or log original hashes
        // For now, leave this empty or add logging if needed
        // You could log or store the original hashes before updating, but it’s not recommended without backups
    }
};