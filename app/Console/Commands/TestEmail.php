<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email {email?}';
    protected $description = 'Test sending email';

    public function handle()
    {
        $email = $this->argument('email') ?: 'minhanh.itqn@gmail.com';

        $this->info('=== EMAIL CONFIGURATION ===');
        $this->info('Driver: ' . config('mail.mailer'));
        $this->info('Host: ' . config('mail.host'));
        $this->info('Port: ' . config('mail.port'));
        $this->info('Encryption: ' . config('mail.encryption'));
        $this->info('From: ' . config('mail.from.address'));
        $this->line('');

        $this->info("Sending test email to: {$email}");

        try {
            Mail::raw('Đây là email test từ Ngọc Huyền Shop. Nếu bạn nhận được email này, hệ thống gửi mail đã hoạt động!', function($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Ngọc Huyền Shop');
            });

            $this->info('✅ Email sent successfully!');
            $this->info('📧 Please check inbox (and spam folder) of: ' . $email);

        } catch (\Exception $e) {
            $this->error('❌ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
