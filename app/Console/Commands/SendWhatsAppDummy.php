<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class SendWhatsAppDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:dummy {phone} {message} {--file_url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a dummy WhatsApp message via Quods direct-send';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsapp): int
    {
        $phone = (string) $this->argument('phone');
        $message = (string) $this->argument('message');
        $fileUrl = (string) $this->option('file_url');
        if ($fileUrl === '') {
            $fileUrl = null;
        }

        $this->info("Sending to {$phone}...");
        $ok = $whatsapp->sendDirect($phone, $message, $fileUrl);

        if ($ok) {
            $this->info('Message sent successfully.');
            return self::SUCCESS;
        }

        $this->error('Failed to send message. Check logs for details.');
        return self::FAILURE;
    }
}
