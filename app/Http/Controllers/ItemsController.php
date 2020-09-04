<?php

namespace App\Http\Controllers;
use App\Http\Resources\Item;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ItemsController
{
    public function index(Response $response, Request $request, $terms = null)
    {
        if ($terms) {
            return Item::filter($response,$request, $terms);
        } else {
            return Item::all();
        }
    }

    public function show(Response $response, $id)
    {
        return Item::show($response, $id);
    }
}
