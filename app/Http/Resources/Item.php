<?php
namespace App\Http\Resources;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;

class Item
{
    static function all()
    {
        return [
            'response' => 'Due to the current restictions of the framework we can\'t get all the items in one go'
        ];
    }

    static function show($response, $id)
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

    static function filter($response, $request, $terms)
    {
        $client = new Client();
        $base = env('API_URL');
        $books = [];
        $page = 1;
        if ($request->has('page')) {
            $page = $request->input('page');
        }
        $max = 10;
        $offset = ($max * $page) - $max;

        $crawler = $client->request('GET', $base . '/items?query=' . $terms . '&max=' . $max .'&offset=' . $offset);

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
}
