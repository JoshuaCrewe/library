<?php
namespace App\Http\Resources;

use Log;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;

class Lists
{
    static function all()
    {
        return [
            'response' => 'Show all the lists'
        ];
    }

    static function show(Request $request, $list = null)
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

        $url = env('API_URL') . '/lists';

        if ($list) {
            $url .= '/' . $list;
        }

        // We are looking for lists
        $crawler = $client->request('GET', $url , [
            'allow_redirects' => true
        ]);

        $items = [];

        $crawler->filter('.list .item')->each(function ($node) use (&$items) {
            $item = [];

            $data = explode( '/', $node->filter('.image img')->attr('src'));

            $item['id'] = $data[1];
            $item['image'] = getenv('API_URL') . '/items/' . $data[1] . '/image-medium';
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

    static function addItem(Request $request, $id)
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

        $url = getenv('API_URL') . '/lists/new?bib_id=' . $id;

        $crawler = $client->request('GET', $url , [
            'allow_redirects' => true
        ]);

        $form = $crawler->selectButton('Save')->form();

        $crawler = $client->submit($form, ['bib_id' => $id , 'action' => 'add']);

        return response()->json([
            'id' => $id,
            'result' => true
        ]);
    }
    static function removeItem(Request $request, $id)
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

        $url = getenv('API_URL') . '/lists';

        $crawler = $client->request('GET', $url , [
            'allow_redirects' => true
        ]);

        $form = $crawler->filter('#item-' . $id )->selectButton('Remove')->form();

        $crawler = $client->submit($form);

        return response()->json([
            'id' => $id,
            'result' => true
        ]);
    }
}
