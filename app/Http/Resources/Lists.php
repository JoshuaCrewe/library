<?php
namespace App\Http\Resources;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

class Lists
{
    static function all()
    {
        return [
            'response' => 'Show all the lists'
        ];
    }

    static function show(Request $request, $list)
    {
        if (!isset($_COOKIE['session'])) {
            $cookie = $request->get('sessionCookie');
        } else {
            $cookie = $_COOKIE['session'];
        }

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client([], null, $cookieJar);

        $result = [];

        // We are looking for lists
        $crawler = $client->request('GET', env('API_URL') . '/lists');

        // We are checking for something on the page that should be there
        if ($crawler->filter('.listmenu')->count() < 1) {
            // If we don't find it check that we are logged in
            self::authenticate();
            // Then check again
            $this->lists($response);

            // If we have already tried logging in
            if (isset($result['perform login'])) {
                // Then send a 404
                abort(404);
            }
            // Make a note of the loging attempt
            $result['perform login'] = true;
        }
        $items = [];

        $crawler->filter('.list .item')->each(function ($node) use (&$items) {
            $item = [];

            $data = explode( '/', $node->filter('.image img')->attr('src'));

            $item['id'] = $data[1];
            $item['image'] = env('API_RUL') . '/items/' . $data[1] . '/image-medium';
            $item['title'] = trim($node->filter('.summary h3')->text(''));
            $item['author'] = trim($node->filter('.summary .author .author')->text(''));
            $items[] = $item;
        });

        $result['items'] = $items;

        // Return the data that we have found
        return response()->json([
            'results'=> $result
        ]);
    }
}
