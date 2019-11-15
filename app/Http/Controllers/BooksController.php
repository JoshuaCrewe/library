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
            $item['title'] = trim($node->filter('.title')->text());
            $item['author'] = trim($node->filter('span[itemprop="name"]')->text());
            $item['summary'] = trim($node->filter('.summarydetail[itemprop="description"]')->text());
            $item['format'] = rtrim(trim($node->filter('.format')->text()), '.');

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
        $book['title'] = $crawler->filter('.item')->filter('.title')->text();
        $book['ISBN'] = $crawler->filter('.item')->filter('span[itemprop="isbn"]')->text();

        return response()->json([
            'results'=> $book
        ]);
    }
}
