<?php

namespace App\Jobs;

use App\Services\NotiFireWhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    protected $phoneNumber;
    protected $message;
    protected $metadata;

    /**
     * Create a new job instance.
     *
     * @param string $phoneNumber
     * @param string $message
     * @param array $metadata
     * @return void
     */
    public function __construct($phoneNumber, $message, array $metadata = [])
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->metadata = $metadata;

        // Set queue from config
        $this->onQueue(config('notifire.queue.queue_name', 'whatsapp'));
        $this->onConnection(config('notifire.queue.connection', 'database'));
    }

    /**
     * Execute the job.
     *
     * @param NotiFire WhatsAppService $whatsappService
     * @return void
     */
    public function handle(NotiFireWhatsAppService $whatsappService)
    {
        try {
            $result = $whatsappService->sendMessage($this->phoneNumber, $this->message);

            if ($result['success']) {
                Log::info('[WhatsApp Queue] Message sent successfully', [
                    'phone' => $this->phoneNumber,
                    'metadata' => $this->metadata,
                ]);
            } else {
    Log::warning('[WhatsApp Queue] Message failed to send', [
        'phone' => $this->phoneNumber,
        'metadata' => $this->metadata,
        'response' => $result,
    ]);

    // Retry if not successful
    if ($this->attempts() < $this->tries) {
        $this->release($this->backoff);
    }
}

        } catch (\Exception $e) {
    Log::error('[WhatsApp Queue] Job failed', [
        'phone' => $this->phoneNumber,
        'error' => $e->getMessage(),
        'metadata' => $this->metadata,
    ]);

    // Retry on exception
    if ($this->attempts() < $this->tries) {
        $this->release($this->backoff);
    }
}
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
{
    Log::error('[WhatsApp Queue] Job permanently failed', [
        'phone' => $this->phoneNumber,
        'message' => $this->message,
        'metadata' => $this->metadata,
        'error' => $exception->getMessage(),
    ]);

    // You can send notification to admin here if needed
}
}
