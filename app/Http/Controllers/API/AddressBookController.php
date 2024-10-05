<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressBookRequest;
use App\Http\Requests\UpdateAddressBookRequest;
use App\Models\AddressBook;

class AddressBookController extends Controller
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
    public function store(StoreAddressBookRequest $request)
    {
        $validated = $request->validated();
//        $isDefault = $validated['isDefault'];
//        dd($validated);
        $owner = auth()->id();
        $validated['user_id'] = $owner;
        $addressBook = AddressBook::where('user_id', $owner)->first();

        if(!$addressBook) {
            $newAddressBook = AddressBook::create($validated);
            $newAddressBook['user_id'] = $owner;
            $validated['isDefault'] = true;// Set default value if not provided
            return response()->json([
                'success' => true,
                'message' => 'Address Book Created Successfully',
                'data' => $newAddressBook
            ]);

        }
        else{
            if($validated['isDefault']){
                AddressBook::where('user_id', $owner)->update(['isDefault' => false]);
//                
                $newAddressBook = AddressBook::create($validated);
                return response()->json(['success' => true, 'addressBook' => $newAddressBook], 201);
            }
            $newAddressBook = AddressBook::create($validated);
            return response()->json(['success' => true, 'addressBook' => $newAddressBook], 201);

        }

//       





    }

    /**
     * Display the specified resource.
     */
    public function show(AddressBook $addressBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddressBook $addressBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressBookRequest $request, AddressBook $addressBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddressBook $addressBook)
    {
        //
    }
}
