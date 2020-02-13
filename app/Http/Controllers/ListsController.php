<?php

namespace App\Http\Controllers;
use App\Http\Resources\Lists;

use Illuminate\Http\Request;

class ListsController
{
    public function index()
    {
        Lists::all();
    }

    public function show(Request $request, $name = null)
    {
        return Lists::show($request, $name);
    }
}
