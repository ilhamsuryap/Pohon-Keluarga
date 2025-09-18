<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class TestWhatsAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {phone} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp service by sending a message';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsappService)
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->info("=== WhatsApp Test ===");
        $this->info("Phone: {$phone}");
        $this->info("Message: {$message}");
        $this->info("API URL: https://api.quods.id/api");
        $this->info("Device Key: UMSZSzMyen40UdD");
        $this->info("API Key: TMeTyUimv75LmlHRlCutowWU2z86QW");
        $this->info("");

        $this->info("Sending message...");
        $result = $whatsappService->sendMessage($phone, $message);

        if ($result) {
            $this->info('✅ Message sent successfully!');
        } else {
            $this->error('❌ Failed to send message.');
            $this->error('Check storage/logs/laravel.log for detailed error information.');
        }

        $this->info("");
        $this->info("You can also test via browser:");
        $this->info("http://localhost/test-whatsapp/{$phone}/" . urlencode($message));

        return 0;
    }
}