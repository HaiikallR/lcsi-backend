<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    private Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Send notification to single device by FCM token
     * 
     * @param string $deviceToken FCM token dari client
     * @param array $data ['judul', 'pesan', 'tipe', 'id']
     * @return bool
     */
    public function sendToDevice(string $deviceToken, array $data): bool
    {
        try {
            if (empty($deviceToken)) {
                Log::warning('Firebase: Device token kosong');
                return false;
            }

            $notification = Notification::create(
                title: $data['judul'] ?? 'Notifikasi LCSI',
                body: $data['pesan'] ?? 'Anda memiliki notifikasi baru'
            );

                $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $data['judul'] ?? 'Notifikasi LCSI',
                'body' => $data['pesan'] ?? 'Anda memiliki notifikasi baru',
            ],
            'data' => [
                'id' => (string)($data['id'] ?? ''),
                'tipe' => $data['tipe'] ?? 'info',
            ],
]);

            $this->messaging->send($message);

            Log::info('Firebase notification sent', [
                'device_token' => substr($deviceToken, 0, 20) . '...',
                'title' => $data['judul'] ?? '',
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Firebase send error: ' . $e->getMessage(), [
                'device_token' => substr($deviceToken, 0, 20) . '...',
            ]);
            return false;
        }
    }

    /**
     * Send to multiple devices
     */
    public function sendToMultipleDevices(array $deviceTokens, array $data): array
    {
        $results = [];

        foreach ($deviceTokens as $token) {
            $results[$token] = $this->sendToDevice($token, $data);
        }

        return $results;
    }

    /**
     * Send ke topic (untuk broadcast ke banyak user)
     * Contoh: /topics/pelanggan_maintenance
     */
    public function sendToTopic(string $topic, array $data): bool
    {
        try {
            $notification = Notification::create(
                title: $data['judul'] ?? 'Notifikasi LCSI',
                body: $data['pesan'] ?? ''
            );

            $message = CloudMessage::fromArray([
                'topic' => $topic,
                'notification' => [
                    'title' => $data['judul'] ?? 'Notifikasi LCSI',
                    'body' => $data['pesan'] ?? '',
                ],
                'data' => [
                    'tipe' => $data['tipe'] ?? 'info',
                ],
            ]);

            $this->messaging->send($message);

            Log::info('Firebase topic notification sent', [
                'topic' => $topic,
                'title' => $data['judul'] ?? '',
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Firebase topic send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Subscribe device ke topic
     */
    public function subscribeToTopic(string $deviceToken, string $topic): bool
    {
        try {
            $this->messaging->subscribeToTopic($topic, [$deviceToken]);

            Log::info('Device subscribed to topic', [
                'topic' => $topic,
                'device_token' => substr($deviceToken, 0, 20) . '...',
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Subscribe to topic error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Unsubscribe dari topic
     */
    public function unsubscribeFromTopic(string $deviceToken, string $topic): bool
    {
        try {
            $this->messaging->unsubscribeFromTopic($topic, [$deviceToken]);
            return true;
        } catch (Exception $e) {
            Log::error('Unsubscribe error: ' . $e->getMessage());
            return false;
        }
    }
}
