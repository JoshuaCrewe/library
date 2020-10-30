<?php

namespace App\Http\Controllers;
use App\Http\Resources\Lists;

use Illuminate\Http\Request;

class ListsController
{
    public function index()
    {
        return Lists::all();
    }

    public function show(Request $request, $name = null)
    {
        return Lists::show($request, $name);
    }

    public function update(Request $request, $id, $action = 'add')
    {
        if ($action === 'add') {
            return Lists::addItem($request, $id);
        }

        if ($action === 'remove') {
            return Lists::removeItem($request, $id);
        }

    }
}
