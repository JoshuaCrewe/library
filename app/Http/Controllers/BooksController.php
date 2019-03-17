<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Goutte\Client;

class BooksController extends Controller
{
    public function search(Response $response, $terms)
    {
        $client = new Client();
        $base = 'https://capitadiscovery.co.uk/cornwall';
        $books = [];

        $crawler = $client->request('GET', $base . '/items?query=' . $terms);

        $crawler->filter('.item')->each(function($node) use (&$books, $base) {
            $item = [];

            $data = explode( '/', $node->filter('img[itemprop="image"]')->attr('src'));

            $item['id'] = $data[1];
            $item['image'] = $base . '/items/' . $data[1] . '/image-medium';
            $item['title'] = trim($node->filter('.title')->text());
            $item['author'] = trim($node->filter('.author span[itemprop="name"]')->text());
            $item['summary'] = trim($node->filter('.summarydetail[itemprop="description"]')->text());
            $item['format'] = rtrim(trim($node->filter('.format')->text()), '.');

            $books[] = $item;
        });

        return response()->json([
            'results'=> $books
        ]);
    }
}
