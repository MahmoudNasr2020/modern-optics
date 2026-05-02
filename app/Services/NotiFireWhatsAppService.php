<?php

namespace App\Services;

use App\Facades\Settings;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class NotiFireWhatsAppService
{
    protected $apiUrl;
    protected $username;
    protected $deviceId;
    protected $enabled;

    public function __construct()
    {
        $this->apiUrl = Settings::get('whatsapp_url');
        $this->username = Settings::get('whatsapp_username');
        $this->deviceId = Settings::get('whatsapp_device_id');
        $this->enabled = config('notifire.enabled');
    }

    /**
     * Send a text message
     *
     * @param string $to Phone number with country code
     * @param string $message Message content
     * @return array
     */
    public function sendMessage($to, $message)
    {
        if (!$this->enabled) {
            $this->log('WhatsApp notifications are disabled');
            return ['success' => false, 'message' => 'WhatsApp notifications are disabled'];
        }

        try {
            $to = $this->formatPhoneNumber($to);

            $client = new Client([
                'timeout' => 30,
            ]);

            $response = $client->post("{$this->apiUrl}/send/message", [
                'json' => [
                    'device_id' => $this->deviceId,
                    'to' => $to,
                    'message' => $message,
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);

            $success = $statusCode >= 200 && $statusCode < 300;

            $this->log('Message sent', [
                'to' => $to,
                'status' => $success,
                'response' => $result
            ]);

            return [
                'success' => $success,
                'data' => $result,
                'status_code' => $statusCode
            ];

        } catch (RequestException $e) {

            $errorMessage = $e->getMessage();

            if ($e->hasResponse()) {
                $errorBody = $e->getResponse()->getBody()->getContents();
                $errorMessage = $errorBody ?: $errorMessage;
            }

            $this->log('Error sending message', [
                'to' => $to,
                'error' => $errorMessage
            ], 'error');

            return [
                'success' => false,
                'message' => $errorMessage
            ];
        }
    }


    /**
     * Send a message with link preview
     *
     * @param string $to Phone number
     * @param string $message Message text
     * @param array $linkPreview Link preview data
     * @return array
     */
    public function sendMessageWithLinkPreview($to, $message, array $linkPreview)
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'WhatsApp notifications are disabled'];
        }

        try {
            $to = $this->formatPhoneNumber($to);

            $response = Http::timeout(30)
                ->post("{$this->apiUrl}/send/link/preview", [
                    'device_id' => $this->deviceId,
                    'to' => $to,
                    'message' => $message,
                    'linkPreview' => $linkPreview,
                ]);

            $result = $response->json();

            $this->log('Message with link preview sent', [
                'to' => $to,
                'status' => $response->successful()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            $this->log('Error sending message with link preview', [
                'to' => $to,
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk messages
     *
     * @param array $numbers Array of phone numbers
     * @param string $message Message content
     * @return array
     */
    public function sendBulkMessage(array $numbers, $message)
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'WhatsApp notifications are disabled'];
        }

        try {
            // Format all phone numbers
            $formattedNumbers = array_map(function($number) {
                return $this->formatPhoneNumber($number);
            }, $numbers);

            // Split into chunks of 20 (API limit)
            $chunks = array_chunk($formattedNumbers, 20);
            $results = [];

            foreach ($chunks as $chunk) {
                $response = Http::timeout(30)
                    ->post("{$this->apiUrl}/send/bulk/message", [
                        'device_id' => $this->deviceId,
                        'message' => $message,
                        'numbers' => $chunk,
                    ]);

                $results[] = [
                    'success' => $response->successful(),
                    'data' => $response->json(),
                    'numbers' => $chunk
                ];

                // Delay between chunks to respect rate limits
                if (count($chunks) > 1) {
                    sleep(config('notifire.rate_limit.delay_between_messages', 2));
                }
            }

            $this->log('Bulk messages sent', [
                'total_numbers' => count($numbers),
                'chunks' => count($chunks)
            ]);

            return [
                'success' => true,
                'results' => $results,
                'total_sent' => count($numbers)
            ];

        } catch (Exception $e) {
            $this->log('Error sending bulk messages', [
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send media (image, video, audio, pdf) via URL
     *
     * @param string $to Phone number
     * @param string $type Media type (image, video, audio, pdf)
     * @param string $mediaUrl URL of the media
     * @param string|null $caption Optional caption
     * @param array $options Additional options (isVoiceNote, fileName)
     * @return array
     */
    public function sendMedia($to, $type, $mediaUrl, $caption = null, array $options = [])
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'WhatsApp notifications are disabled'];
        }

        try {
            $to = $this->formatPhoneNumber($to);

            $data = [
                'device_id' => $this->deviceId,
                'to' => $to,
                'type' => $type,
                'mediaUrl' => $mediaUrl,
            ];

            if ($caption) {
                $data['caption'] = $caption;
            }

            // Add type-specific options
            if (isset($options['isVoiceNote'])) {
                $data['isVoiceNote'] = $options['isVoiceNote'];
            }

            if (isset($options['fileName'])) {
                $data['fileName'] = $options['fileName'];
            }

            $response = Http::timeout(60) // Longer timeout for media
            ->post("{$this->apiUrl}/send/media", $data);

            $result = $response->json();

            $this->log('Media sent', [
                'to' => $to,
                'type' => $type,
                'status' => $response->successful()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            $this->log('Error sending media', [
                'to' => $to,
                'type' => $type,
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send contact
     *
     * @param string $to Recipient phone number
     * @param string $contactName Contact's name
     * @param string $contactPhone Contact's phone number
     * @return array
     */
    public function sendContact($to, $contactName, $contactPhone)
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'WhatsApp notifications are disabled'];
        }

        try {
            $to = $this->formatPhoneNumber($to);
            $contactPhone = $this->formatPhoneNumber($contactPhone);

            $response = Http::timeout(30)
                ->post("{$this->apiUrl}/send/contact", [
                    'device_id' => $this->deviceId,
                    'to' => $to,
                    'contact_name' => $contactName,
                    'contact_phone' => $contactPhone,
                ]);

            $result = $response->json();

            $this->log('Contact sent', [
                'to' => $to,
                'contact' => $contactName,
                'status' => $response->successful()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            $this->log('Error sending contact', [
                'to' => $to,
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send location
     *
     * @param string $to Recipient phone number
     * @param float $latitude Latitude
     * @param float $longitude Longitude
     * @return array
     */
    public function sendLocation($to, $latitude, $longitude)
    {
        if (!$this->enabled) {
            return ['success' => false, 'message' => 'WhatsApp notifications are disabled'];
        }

        try {
            $to = $this->formatPhoneNumber($to);

            $response = Http::timeout(30)
                ->post("{$this->apiUrl}/send/location", [
                    'device_id' => $this->deviceId,
                    'to' => $to,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);

            $result = $response->json();

            $this->log('Location sent', [
                'to' => $to,
                'coordinates' => "$latitude, $longitude",
                'status' => $response->successful()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            $this->log('Error sending location', [
                'to' => $to,
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get device info
     *
     * @return array
     */
    public function getDeviceInfo()
    {
        try {
            $response = Http::timeout(30)
                ->post("{$this->apiUrl}/device/info", [
                    'device_id' => $this->deviceId,
                    'username' => $this->username,
                ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            $this->log('Error getting device info', [
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Connect device
     *
     * @return array
     */
    public function connectDevice()
    {
        try {
            $response = Http::timeout(30)
                ->post("{$this->apiUrl}/device/connect", [
                    'device_id' => $this->deviceId,
                    'username' => $this->username,
                ]);

            $result = $response->json();

            $this->log('Device connection attempted', [
                'status' => $response->successful(),
                'response' => $result
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            $this->log('Error connecting device', [
                'error' => $e->getMessage()
            ], 'error');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number with country code
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // If doesn't start with +, add default country code
        if (!str_starts_with($phone, '+')) {
            $countryCode = config('notifire.default_country_code', '+966');

            // Remove leading zero if exists
            $phone = ltrim($phone, '0');

            $phone = $countryCode . $phone;
        }

        return $phone;
    }

    /**
     * Log activity
     *
     * @param string $message
     * @param array $context
     * @param string $level
     * @return void
     */
    protected function log($message, array $context = [], $level = 'info')
    {
        if (!config('notifire.log.enabled')) {
            return;
        }

        $channel = config('notifire.log.channel', 'daily');

        Log::channel($channel)->{$level}("[NotiFire WhatsApp] $message", $context);
    }

    /**
     * Validate phone number
     *
     * @param string $phone
     * @return bool
     */
    public function validatePhoneNumber($phone)
    {
        if (!config('notifire.phone_validation.enabled')) {
            return true;
        }

        $formatted = $this->formatPhoneNumber($phone);
        $length = strlen(str_replace('+', '', $formatted));

        $minLength = config('notifire.phone_validation.min_length', 10);
        $maxLength = config('notifire.phone_validation.max_length', 15);

        return $length >= $minLength && $length <= $maxLength;
    }
}
