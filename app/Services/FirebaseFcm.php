<?php
namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class FirebaseFcm
{
    protected string $serviceAccountPath;
    protected string $projectId;
    protected Client $http;
    protected LoggerInterface|null $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->serviceAccountPath = base_path('life-pulse-4ff3a.json');
        $this->logger = $logger;
        $this->http = new Client([
            'timeout' => 10,
            // 'verify' => false // لا تقم بتعطيل التحقق في بيئة انتاج
        ]);

        if (!file_exists($this->serviceAccountPath)) {
            throw new \InvalidArgumentException("Service account file not found at {$this->serviceAccountPath}");
        }

        $json = json_decode((string) file_get_contents($this->serviceAccountPath), true);
        if (empty($json['project_id'])) {
            throw new \RuntimeException("project_id not found in service account json");
        }

        $this->projectId = $json['project_id'];
    }

    /**
     * الحصول على Access Token باستخدام google/auth
     *
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken(): string
    {
        try {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

            // نمرر محتوى JSON (كمصفوفة) إلى ServiceAccountCredentials
            $jsonKey = json_decode((string) file_get_contents($this->serviceAccountPath), true);

            $creds = new ServiceAccountCredentials($scopes, $jsonKey);
            $authToken = $creds->fetchAuthToken();

            if (empty($authToken['access_token'])) {
                throw new \RuntimeException('Failed to obtain access token from service account');
            }

            return $authToken['access_token'];
        } catch (\Throwable $ex) {
            if ($this->logger) {
                $this->logger->error('Failed to get Firebase access token', ['exception' => $ex]);
            }
            throw new \Exception('Failed to obtain Firebase access token', 0, $ex);
        }
    }

    /**
     * إرسال إشعار إلى جهاز واحد (device token)
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     * @param array|null $data
     * @return array ['ok' => bool, 'status' => int, 'body' => string]
     */
    public function sendToDevice(string $deviceToken, string $title = 'عنوان الإشعار', string $body = 'محتوى الإشعار', ?array $data = null): array
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $accessToken = $this->getAccessToken();

        // بناء الرسالة حسب HTTP v1 API
        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body
                ],
                'data' => array_merge(
                    ['click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'sound' => 'default'],
                    $data ?? []
                ),
                'android' => [
                    'notification' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'category' => 'NEW_MESSAGE_CATEGORY',
                            'sound' => 'default'
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->http->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type'  => 'application/json; charset=utf-8',
                ],
                'json' => $message,
            ]);

            $status = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($this->logger) {
                $this->logger->info('FCM sendToDevice response', ['status' => $status, 'response' => $body]);
            }

            return ['ok' => $status >= 200 && $status < 300, 'status' => $status, 'body' => $body];
        } catch (RequestException $ex) {
            $resp = $ex->hasResponse() ? (string) $ex->getResponse()->getBody() : $ex->getMessage();
            if ($this->logger) {
                $this->logger->error('FCM sendToDevice failed', ['error' => $resp, 'exception' => $ex]);
            }
            return ['ok' => false, 'status' => $ex->getCode() ?: 500, 'body' => $resp];
        } catch (\Throwable $ex) {
            if ($this->logger) {
                $this->logger->error('FCM sendToDevice unexpected error', ['exception' => $ex]);
            }
            return ['ok' => false, 'status' => 500, 'body' => $ex->getMessage()];
        }
    }

    /**
     * إرسال إشعار إلى Topic
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array|null $data
     * @return array
     */
    public function sendToTopic(string $topic, string $title, string $body, ?array $data = null): array
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $accessToken = $this->getAccessToken();

        $message = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => array_merge(
                    ['click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'sound' => 'default'],
                    $data ?? []
                ),
                'android' => [
                    'notification' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'category' => 'NEW_MESSAGE_CATEGORY'
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->http->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'json' => $message
            ]);

            $status = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($this->logger) {
                $this->logger->info('FCM sendToTopic response', ['topic' => $topic, 'status' => $status, 'response' => $body]);
            }

            return ['ok' => $status >= 200 && $status < 300, 'status' => $status, 'body' => $body];
        } catch (RequestException $ex) {
            $resp = $ex->hasResponse() ? (string) $ex->getResponse()->getBody() : $ex->getMessage();
            if ($this->logger) {
                $this->logger->error('FCM sendToTopic failed', ['topic' => $topic, 'error' => $resp, 'exception' => $ex]);
            }
            return ['ok' => false, 'status' => $ex->getCode() ?: 500, 'body' => $resp];
        } catch (\Throwable $ex) {
            if ($this->logger) {
                $this->logger->error('FCM sendToTopic unexpected error', ['topic' => $topic, 'exception' => $ex]);
            }
            return ['ok' => false, 'status' => 500, 'body' => $ex->getMessage()];
        }
    }
}
