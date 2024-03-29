<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;

use App\Http\Middleware;

class DashboardController
{
    public function index(Response $response, Request $request)
    {
        $cookie = $request->get('sessionCookie');
        $base = env('API_URL');

        if (!$cookie) {
            $cookie = '';
        }

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        /* dd($cookieJar); */
        $client = new Client(HttpClient::create(), null, $cookieJar);
        $crawler = $client->request('GET', $base . '/account');

        $result = [];
        $welcome = '';
        $logout = $crawler->filter('#logout')->text('');
        if ($logout == '') {
            if(isset($_COOKIE['session'])) {
                unset($_COOKIE['session']);
            }
            App\Http\Middleware\Login::class;
            $logout = $crawler->filter('#logout')->text('');
        };

        $crawler->filter('.accountSummary')->each(function ($node) use(&$welcome) {
            $welcome = trim($node->text());
        });
        $loans = []; // get each bit individual like
        $crawler->filter('#loans tbody tr')->each(function ($node) use(&$loans, &$base) {
            $item = [];

            $data = explode( '/', $node->filter('img')->attr('src'));
            $item['id'] = $data[1];

            $item['image'] = $base . '/items/' . $data[1] . '/image-medium';
            $item['title'] = trim($node->filter('.loanItemLink')->text(''));
            $item['author'] = trim($node->filter('.author')->text(''));
            $item['due'] = trim($node->filter('.accDue')->text(''));
            $item['fine'] = trim($node->filter('.accFines')->text(''));
            $item['renewCount'] = trim($node->filter('.accRenews')->text(''));
            $loans[] = $item;
        });

        $client = new Client(HttpClient::create(), null, $cookieJar);
        $crawler = $client->request('GET', env('API_URL') . '/account/reservations');
        $reservations = []; // get each bit individual like
        $crawler->filter('#reservations tbody tr')->each(function ($node) use(&$reservations, &$base) {
            $item = [];

            $data = explode( '/', $node->filter('img')->attr('src'));
            $item['id'] = $data[1];
            $item['image'] = $base . '/items/' . $data[1] . '/image-medium';

            $item['title'] = trim($node->filter('a')->text(''));
            $item['author'] = trim($node->filter('.author')->text(''));
            $item['position'] = trim($node->filter('.accReservationPos')->text(''));
            $item['reserveDate'] = trim($node->filter('td:nth-of-type(2)')->text(''));
            $reservations[] = $item;
        });

        return response()->json([
            'welcome' => $welcome,
            'loans' => array_values(array_filter($loans)),
            'reservations' => array_values(array_filter($reservations))
        ]);
    }

    public function barcode(Response $response, Request $request, $barcode)
    {

        $secret = Crypt::encrypt($barcode);

        // Use the barcode to log in.
        // If successful then save the encrypted version to the browser
        // Redirect user to the dashboard ?

        $client = new Client();
        $crawler = $client->request('GET', env('API_URL') . '/login');

        // Find the login form ...
        $form = $crawler->selectButton('Login')->form();
        // Fill in the fields and send it off
        $crawler = $client->submit($form, array('barcode' => $barcode, 'institutionId' => ''));

        $logout = $crawler->filter('#logout_button')->attr('value');
        // dd($logout);

        if ($logout !== 'Logout') {
            unset($_COOKIE['barcode']); 
            setcookie('barcode', null, -1, '/'); 
            return response()->json([
                'success' => false
            ]);
        }

        $cookie = setcookie('barcode', $secret, [
            'expires' => time()+60*60*24*60,
            'path' => '/',
            'domain' => env('APP_DOMAIN'),
            'secure' => $_SERVER['REQUEST_SCHEME'] === 'https',
            'httponly' => true,
            'samesite' => 'lax'
        ]);

        return response()->json([
            'success' => true,
            'cookies' => $cookie,
            'encrypted'=> Crypt::encrypt($barcode),
            // 'secret'=> $secret,
            // 'decrypted'=> Crypt::decrypt($secret),
        ]);
    }

    public function reserve(Response $response, Request $request, $id)
    {
        if (!isset($_COOKIE['session'])) {
            $cookie = $request->get('sessionCookie');
        } else {
            $cookie = $_COOKIE['session'];
        }

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client(HttpClient::create(), null, $cookieJar);

        $result = [];

        $url = env('API_URL') . '/items/' . $id;

        $crawler = $client->request('GET', $url , [
            'allow_redirects' => true
        ]);

        $form = $crawler->selectButton('Reserve')->form();

        $crawler = $client->submit($form);

        return response()->json([
            'id' => $id,
            'result' => true
        ]);

    }

    public function cancel(Response $response, Request $request, $id)
    {
        if (!isset($_COOKIE['session'])) {
            $cookie = $request->get('sessionCookie');
        } else {
            $cookie = $_COOKIE['session'];
        }

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client(HttpClient::create(), null, $cookieJar);

        $url = env('API_URL') . '/account/reservations';

        $crawler = $client->request('GET', $url , [
            'allow_redirects' => true
        ]);

        $item = $crawler->filter('#reservations tbody tr a[href$=' . $id . ']');
        if (!$item) {
            return response()->json([
                'result' => false
            ]);
        }
        $form = $item->ancestors()->ancestors()->selectButton('Cancel')->form();

        $crawler = $client->submit($form);

        return response()->json([
            'result' => true,
        ]);

    }

    public function renew(Response $response, Request $request, $id)
    {
        if (!isset($_COOKIE['session'])) {
            $cookie = $request->get('sessionCookie');
        } else {
            $cookie = $_COOKIE['session'];
        }

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client(HttpClient::create(), null, $cookieJar);

        $url = env('API_URL') . '/account';

        $crawler = $client->request('GET', $url , [
            'allow_redirects' => true
        ]);

        $item = $crawler->filter('#loans tbody tr a[href$="' . $id . '"]')->ancestors()->ancestors();
        if (!$item) {
            return response()->json([
                'result' => false
            ]);
        }
        $form = $item->selectButton('Renew')->form();

        $crawler = $client->submit($form);

        return response()->json([
            'result' => true,
            'form' => $form
        ]);

    }

}
