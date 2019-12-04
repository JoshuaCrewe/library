<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Goutte\Client;

class BooksController extends Controller
{
    public function login(Response $response, $barcode)
    {
        $client = new Client();
        $result = [];
        $crawler = $client->request('GET', 'https://capitadiscovery.co.uk/cornwall/login');
        $form = $crawler->selectButton('Login')->form();
        $crawler = $client->submit($form, array('barcode' => $barcode, 'institutionId' => ''));
        $crawler->filter('.accountSummary')->each(function ($node) {
            print trim($node->text());
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
