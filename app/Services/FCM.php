<?php
namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FCM
{
    protected static string $serviceAccountPath = __DIR__ . '/../../life-pulse-4ff3a-90a4659464d2.json';

    protected static function getProjectId(): string
    {
        $json = json_decode((string) file_get_contents(self::$serviceAccountPath), true);
        if (empty($json['project_id'])) {
            throw new \RuntimeException("project_id not found in service account json");
        }
        return $json['project_id'];
    }

    protected static function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $jsonKey = json_decode((string) file_get_contents(self::$serviceAccountPath), true);
        $creds = new ServiceAccountCredentials($scopes, $jsonKey);
        $authToken = $creds->fetchAuthToken();

        if (empty($authToken['access_token'])) {
            throw new \RuntimeException('Failed to obtain access token from service account');
        }

        return $authToken['access_token'];
    }

    public static function sendToDevice(string $deviceToken, string $title = 'عنوان الإشعار', string $body = 'محتوى الإشعار', ?array $data = null): array
    {
        $projectId = self::getProjectId();
        $accessToken = self::getAccessToken();
        $http = new Client(['timeout' => 10]);

        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => ['title' => $title, 'body' => $body],
                'data' => array_merge(['click_action'=>'FLUTTER_NOTIFICATION_CLICK','sound'=>'default'], $data??[]),
                'android' => ['notification'=>['click_action'=>'FLUTTER_NOTIFICATION_CLICK']],
                'apns' => ['payload'=>['aps'=>['category'=>'NEW_MESSAGE_CATEGORY','sound'=>'default']]]
            ]
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        try {
            $response = $http->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json; charset=utf-8',
                ],
                'json' => $message
            ]);

            $status = $response->getStatusCode();
            $bodyResp = (string)$response->getBody();

            return ['ok'=>$status>=200 && $status<300,'status'=>$status,'body'=>$bodyResp];
        } catch (RequestException $ex) {
            $resp = $ex->hasResponse() ? (string)$ex->getResponse()->getBody() : $ex->getMessage();
            return ['ok'=>false,'status'=>$ex->getCode()?:500,'body'=>$resp];
        } catch (\Throwable $ex) {
            return ['ok'=>false,'status'=>500,'body'=>$ex->getMessage()];
        }
    }

    public static function sendToTopic(string $topic, string $title, string $body, ?array $data = null): array
    {
        $projectId = self::getProjectId();
        $accessToken = self::getAccessToken();
        $http = new Client(['timeout' => 10]);

        $message = [
            'message' => [
                'topic' => $topic,
                'notification' => ['title'=>$title,'body'=>$body],
                'data' => array_merge(['click_action'=>'FLUTTER_NOTIFICATION_CLICK','sound'=>'default'], $data??[]),
                'android' => ['notification'=>['click_action'=>'FLUTTER_NOTIFICATION_CLICK']],
                'apns' => ['payload'=>['aps'=>['category'=>'NEW_MESSAGE_CATEGORY']]]
            ]
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        try {
            $response = $http->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'json' => $message
            ]);

            $status = $response->getStatusCode();
            $bodyResp = (string)$response->getBody();

            return ['ok'=>$status>=200 && $status<300,'status'=>$status,'body'=>$bodyResp];
        } catch (RequestException $ex) {
            $resp = $ex->hasResponse() ? (string)$ex->getResponse()->getBody() : $ex->getMessage();
            return ['ok'=>false,'status'=>$ex->getCode()?:500,'body'=>$resp];
        } catch (\Throwable $ex) {
            return ['ok'=>false,'status'=>500,'body'=>$ex->getMessage()];
        }
    }
}
