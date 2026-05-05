<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Google\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    private string $projectId;
    private string $serviceAccountPath;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID', 'mobileapplicationlcsi-5fd48');
        $this->serviceAccountPath = storage_path('app/firebase/service-account.json');
    }

    /**
     * Ambil Access Token untuk FCM V1 API
     */
    private function getAccessToken(): ?string
    {
        try {
            if (!file_exists($this->serviceAccountPath)) {
                Log::error('Firebase service account file tidak ditemukan: ' . $this->serviceAccountPath);
                return null;
            }

            $client = new Client();
            $client->setAuthConfig($this->serviceAccountPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();
            
            return $token['access_token'] ?? null;
        } catch (Exception $e) {
            Log::error('Error getting FCM access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kirim notifikasi ke 1 device
     * 
     * @param string $fcmToken Token FCM dari client
     * @param string $judul Judul notifikasi
     * @param string $pesan Isi pesan
     * @param array $data Data tambahan (kategori, id notifikasi, dll)
     * @return bool Success or failed
     */
    public function kirim(string $fcmToken, string $judul, string $pesan, array $data = []): bool
    {
        try {
            if (empty($fcmToken)) {
                Log::warning('FCM Token kosong');
                return false;
            }

            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                Log::error('Gagal mendapatkan FCM access token');
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $judul,
                        'body' => $pesan,
                    ],
                    'data' => array_merge([
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ], $data),
                ],
            ];

            $response = Http::withToken($accessToken)
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('FCM: Notifikasi berhasil dikirim', [
                    'judul' => $judul,
                    'token' => substr($fcmToken, 0, 20) . '...',
                ]);
                return true;
            }

            Log::error('FCM: Gagal mengirim', [
                'status' => $response->status(),
                'body' => $response->json(),
                'judul' => $judul,
            ]);

            return false;
        } catch (Exception $e) {
            Log::error('FCM Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Kirim ke multiple devices
     */
    public function kirimKeMultiple(array $fcmTokens, string $judul, string $pesan, array $data = []): array
    {
        $hasil = [];

        foreach ($fcmTokens as $token) {
            $hasil[$token] = $this->kirim($token, $judul, $pesan, $data);
        }

        return $hasil;
    }
}
