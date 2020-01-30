<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

class BooksController extends Controller
{
    private function authenticate()
    {
        // @TODO get the barcode from cookie / local storage
        // For now we will get the barcode from secret store
        $barcode = env('BARCODE');

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
            setcookie('session', $values['session'], 0, "/");
            // This is now the cookie we want
            $cookie = $values['session'];
        }

        // Hand it over
        return $cookie;
    }

    private function getCookie() 
    {
        if(isset($_COOKIE['session'])) {
            $cookie = $_COOKIE['session'];
        } else {
            $cookie = self::authenticate();
        };
        return $cookie;
    }

    public function login(Response $response)
    {
        // @TODO
        // Take the barcode and save it in an encrypted cookie / local storage ?

        $result = [];
        $cookie = self::getCookie();

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client([], null, $cookieJar);
        $crawler = $client->request('GET', env('API_URL') . '/account');

        $crawler->filter('.accountSummary')->each(function ($node) use(&$result) {
            $result[] = trim($node->text());
        });

        return response()->json([
            'results'=> $result,
        ]);
    }


    public function lists(Response $response)
    {
        $cookie = self::getCookie();

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

    public function search(Response $response, $terms)
    {
        $client = new Client();
        $base = env('API_URL');
        $books = [];


        $crawler = $client->request('GET', $base . '/items?query=' . $terms . '&max=12');

        $crawler->filter('.item')->each(function($node) use (&$books, $base) {
            $item = [];

            $data = explode( '/', $node->filter('img[itemprop="image"]')->attr('src'));

            $item['id'] = $data[1];
            $item['image'] = $base . '/items/' . $data[1] . '/image-medium';
            $item['title'] = trim($node->filter('.title')->text(''));
            $item['author'] = trim($node->filter('span[itemprop="name"]')->text(''));
            $item['summary'] = trim($node->filter('.summarydetail[itemprop="description"]')->text(''));
            $item['format'] = rtrim(trim($node->filter('.format')->text('')), '.');

            $books[] = $item;
        });

        return response()->json([
            'results'=> $books
        ]);
    }

    public function single(Response $response, $id)
    {
        $client = new Client();
        $base = env('API_URL');
        $book = [];

        $crawler = $client->request('GET', $base . '/items/' . $id);

        $book['id'] = $id;

        $data = explode( '/', $crawler->filter('img[itemprop="image"]')->attr('src'));
        $book['image'] = $base . '/items/' . $data[1] . '/image-medium';

        $book['title'] = $crawler->filter('.item')->filter('.title')->text('');
        $book['author'] = trim($crawler->filter('.description > .author > span[itemprop="creator"] > span[itemprop="name"]')->text(''));
        $book['summary'] = trim($crawler->filter('.summarydetail > p[itemprop="description"]')->text(''));
        $book['ISBN'] = $crawler->filter('.item')->filter('span[itemprop="isbn"]')->text('');
        $book['genres'] = $crawler->filter('.item')->filter('span[itemprop="genre"]')->extract(['_text']);

        $book['status'] = $crawler->filter('.item #availability > .options')->html('<h3>Not Available</h3>');

        return response()->json([
            'results'=> $book
        ]);
    }
}
