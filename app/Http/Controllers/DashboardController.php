<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

class DashboardController
{
    public function index(Response $response, Request $request)
    {
        $cookie = $request->get('sessionCookie');

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client([], null, $cookieJar);
        $crawler = $client->request('GET', env('API_URL') . '/account');

        $result = [];

        $crawler->filter('.accountSummary')->each(function ($node) use(&$result) {
            $result[] = trim($node->text());
        });

        return response()->json([
            'results'=> $result,
        ]);
    }

}
