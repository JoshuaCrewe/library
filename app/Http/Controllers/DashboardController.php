<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

use App\Http\Middleware;

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
        $logout = $crawler->filter('#logout')->text('');
        if ($logout == '') {
            App\Http\Middleware\Login::class;
            $logout = $crawler->filter('#logout')->text('');
        }

        $crawler->filter('.accountSummary')->each(function ($node) use(&$result) {
            $result[] = trim($node->text());
        });

        return response()->json([
            'results'=> $result,
        ]);
    }

    public function barcode(Response $response, Request $request, $barcode)
    {

        $secret = Crypt::encrypt($barcode);

        // Use the barcode to log in.
        // If successful then save the encrypted version to the browser
        // Redirect user to the dashboard ?
        $cookie = setcookie('barcode', $secret, false, '/');

        $client = new Client();
        $crawler = $client->request('GET', env('API_URL') . '/login');

        // Find the login form ...
        $form = $crawler->selectButton('Login')->form();
        // Fill in the fields and send it off
        $crawler = $client->submit($form, array('barcode' => $barcode, 'institutionId' => ''));

        $logout = $crawler->filter('#logout')->text('');

        if ($logout == '') {
            unset($_COOKIE['barcode']); 
            setcookie('barcode', null, -1, '/'); 
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'cookies' => $cookie,
            'encrypted'=> Crypt::encrypt($barcode),
            'secret'=> $secret,
            'decrypted'=> Crypt::decrypt($secret),
        ]);
    }

}
