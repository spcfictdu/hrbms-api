<?php

namespace App\Http\Controllers\Discount;

use Illuminate\Http\Request;
use App\Models\Discount\Discount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\IndexDiscountRequest;
use App\Repositories\Discount\IndexDiscountRepository;

class DiscountController extends Controller
{

    protected $index;
    public function __construct(

        IndexDiscountRepository $index
    ){
        $this->index = $index;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexDiscountRequest $request)
    {
        return $this->index->execute();
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
