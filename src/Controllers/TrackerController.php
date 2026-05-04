<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class TrackerController
{
    public function track(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody(), true);

        $visitorId = $data['visitorId'] ?? md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $city = $data['city'] ?? $this->getCityByIp($_SERVER['REMOTE_ADDR']);

        $visitData = [
            'visitor_id' => $visitorId,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'city' => $city,
            'device' => $data['device'] ?? 'Unknown',
            'browser' => $data['browser'] ?? 'Unknown',
            'os' => $data['os'] ?? 'Unknown',
            'page_url' => $data['pageUrl'] ?? '',
            'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
            'hour' => date('H'),
            'date' => date('Y-m-d')
        ];

        $existing = Capsule::table('visits')
            ->where('visitor_id', $visitorId)
            ->where('date', date('Y-m-d'))
            ->where('hour', date('H'))
            ->exists();

        Capsule::table('visits')->insert($visitData);

        $result = [
            'success' => true,
            'unique' => !$existing,
            'visitor_id' => $visitorId
        ];

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function getCityByIp($ip)
    {
        try {
            $ch = curl_init("http://ip-api.com/json/{$ip}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            $data = json_decode(curl_exec($ch), true);
            curl_close($ch);
            return $data['city'] ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}