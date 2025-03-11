<?php

namespace App\Http\Controllers\Addon;

use Illuminate\Http\Request;
use App\Models\Amenity\Addon;
use App\Http\Controllers\Controller;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Addon::all();
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
