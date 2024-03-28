<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soup;

class SoupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soups = Soup::all();
        return view('Admin/Soups', compact('soups'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Soup $soup)
    {
        //$contitions=Condition::all();
        return view('Admin/Soup', compact('soup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

       //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        return view('admin.soups.edit', compact('name', 'description'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soup $soup)
    {
        dd('to delete', $soup);
    }

}
