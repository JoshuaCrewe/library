<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

class BooksController extends Controller
{
    public function handleLogin()
    {
        $barcode = env('BARCODE');
        $client = new Client();
        $result = [];
        $crawler = $client->request('GET', 'https://capitadiscovery.co.uk/cornwall/login');
        $form = $crawler->selectButton('Login')->form();
        $crawler = $client->submit($form, array('barcode' => $barcode, 'institutionId' => ''));

        // Response
        $cookieJar = $client->getCookieJar();
        // var_dump($cookieJar);
        $values = $cookieJar->allValues('https://capitadiscovery.co.uk/cornwall/');
        $result['values'] = $values;

        // @TODO
        // We do not want to loop through them all really. We just want the session
        foreach ($values as $name => $value) {

            // @TODO
            // Ideally we want to set this for the session rather than what is here
            setcookie($name, $value, time() + (86400 * 30), "/");

            $cookie = $value;
        }

        return $cookie;
    }

    public function login(Response $response, $barcode)
    {
        // Take the barcode and save it in an encrypted cookie / local storage ?

        // 
        $client = new Client();
        $result = [];
        $cookie = self::getAccess();

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client([], null, $cookieJar);
        $crawler = $client->request('GET', 'https://capitadiscovery.co.uk/cornwall/account');

        $crawler->filter('.accountSummary')->each(function ($node) use(&$result) {
            $result[] = trim($node->text());
        });

        return response()->json([
            'results'=> $result,
        ]);
    }

    private function getAccess() 
    {
        if(isset($_COOKIE['session'])) {
            $cookie = $_COOKIE['session'];
        } else {
            $cookie = $this->handleLogin();
        };
        return $cookie;
    }

    public function lists(Response $response)
    {
        $cookie = self::getAccess();

        $cookieJar = new CookieJar(true);
        $cookie = new Cookie('session', $cookie);
        $cookieJar->set($cookie);

        $client = new Client([], null, $cookieJar);

        $result = [];

        $crawler = $client->request('GET', 'https://capitadiscovery.co.uk/cornwall/lists');

        $lists = $crawler->filter('.listmenu')->count();

        if ($crawler->filter('.listmenu')->count() < 1) {
            $this->handleLogin();
            $this->lists($response);
            $result['perform login'] = true;
        }

        $crawler->filter('.listmenu')->each(function ($node) use (&$result) {
            $result[] = trim($node->text());
        });

        return response()->json([
            'results'=> $result
        ]);

    }
    public function search(Response $response, $terms)
    {
        $client = new Client();
        $base = 'https://capitadiscovery.co.uk/cornwall';
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
        $base = 'https://capitadiscovery.co.uk/cornwall';
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

        // $book['status'] = trim($crawler->filter('.item')->filter('#availability > .options')->html());
        $book['status'] = $crawler->filter('.item #availability > .options')->html('<h3>Not Available</h3>');

        return response()->json([
            'results'=> $book
        ]);
    }
}
