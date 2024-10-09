<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckOutRequest;
use App\Http\Requests\UpdateCheckOutRequest;
use App\Models\CheckOut;

class CheckOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreCheckOutRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(CheckOut $checkOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CheckOut $checkOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckOutRequest $request, CheckOut $checkOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CheckOut $checkOut)
    {
        //
    }
}
