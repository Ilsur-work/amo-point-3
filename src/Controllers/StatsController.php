<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class StatsController
{
    public function login(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody(), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = Capsule::table('users')->where('username', $username)->first();

        if ($user && password_verify($password, $user->password_hash)) {
            $token = bin2hex(random_bytes(32));

            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['token'] = $token;

            $result = ['success' => true, 'token' => $token];
        } else {
            $result = ['success' => false, 'message' => 'Invalid credentials'];
        }

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getStats(Request $request, Response $response): Response
    {
        $hourlyStats = Capsule::table('visits')
            ->selectRaw('hour, COUNT(DISTINCT visitor_id) as unique_visits, COUNT(*) as total_visits')
            ->where('date', date('Y-m-d'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $cityStats = Capsule::table('visits')
            ->selectRaw('city, COUNT(DISTINCT visitor_id) as unique_visitors')
            ->groupBy('city')
            ->orderByDesc('unique_visitors')
            ->limit(10)
            ->get();

        $total = Capsule::table('visits')
            ->selectRaw('COUNT(DISTINCT visitor_id) as total_unique, COUNT(*) as total_visits')
            ->first();

        $result = [
            'hourly' => $hourlyStats,
            'cities' => $cityStats,
            'total_unique' => $total->total_unique ?? 0,
            'total_visits' => $total->total_visits ?? 0
        ];

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}