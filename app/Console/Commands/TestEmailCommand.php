<?php

namespace App\Console\Commands;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email} {--name=Test User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test OTP email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->option('name');

        $this->info("Testing email configuration...");
        $this->info("SMTP Host: " . config('mail.mailers.smtp.host'));
        $this->info("SMTP Port: " . config('mail.mailers.smtp.port'));
        $this->info("SMTP Encryption: " . config('mail.mailers.smtp.encryption'));
        $this->info("From Address: " . config('mail.from.address'));

        // Create a temporary user object for testing
        $testUser = new User();
        $testUser->email = $email;
        $testUser->name = $name;

        $testOtpCode = '123456';

        try {
            $this->info("Sending test email to: {$email}");
            
            Mail::to($email)->send(new OtpMail($testUser, $testOtpCode, 'Email Configuration Test'));
            
            $this->info("✅ Email sent successfully!");
            $this->info("Check your email inbox and spam folder.");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email:");
            $this->error($e->getMessage());
            
            $this->info("\n🔧 Troubleshooting tips:");
            $this->info("1. Check your MAIL_ENCRYPTION setting (should be 'ssl' for port 465 or 'tls' for port 587)");
            $this->info("2. Verify your SMTP credentials are correct");
            $this->info("3. Check if your hosting provider blocks outgoing SMTP connections");
            $this->info("4. Try using port 587 with TLS encryption instead of port 465 with SSL");
            
            return 1;
        }

        return 0;
    }
}
