<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

use Closure;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($_COOKIE['barcode'])) {
            $cookie = Self::getCookie($request);
        }

        return $next($request);
    }

    private function authenticate($request)
    {
        $secret = $_COOKIE['barcode'];

        $barcode = Crypt::decrypt($secret);

        if (!$barcode) {
            // We don't have all we need to login
            // We should redirect to the login screen with a notice?
            return false;
        }

        // Set up a new client and fetch the login page
        $client = new Client();
        $crawler = $client->request('GET', env('API_URL') . '/login');

        // Find the login form ...
        $form = $crawler->selectButton('Login')->form();
        // Fill in the fields and send it off
        $crawler = $client->submit($form, array('barcode' => $barcode, 'institutionId' => ''));

        // We want to save the cookie we get back
        $cookieJar = $client->getCookieJar();
        // Make is specific to the domain we are dealing with
        $values = $cookieJar->allValues(env('API_URL') . '/');

        // By default there is no session cookie
        $cookie = null;

        // If we do get one from the cookie jar
        if (isset($values['session'])) {
            // Then save it on this end
            setcookie('session', $values['session'], [
                // We are setting a short time on the session (5 minutes)
                'expires' => time() + (60*5),
                'path' => '/',
                'domain' => env('APP_DOMAIN'),
                'secure' => $_SERVER['REQUEST_SCHEME'] === 'https',
                'httponly' => true,
                'samesite' => 'lax'
            ]);
            // This is now the cookie we want
            $cookie = $values['session'];
        }

        // Hand it over
        $request->attributes->add(['sessionCookie' => $cookie]);
        return $cookie;
    }

    private function getCookie($request) 
    {
        if(isset($_COOKIE['session'])) {
            $cookie = $_COOKIE['session'];
            $request->attributes->add(['sessionCookie' => $cookie]);
        } else {
            $cookie = self::authenticate($request);
        };
        return $cookie;
    }
}
