<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressBookRequest;
use App\Http\Requests\UpdateAddressBookRequest;
use Illuminate\Http\Request;
use App\Models\AddressBook;


class AddressBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $owner = auth()->id();
        $addressBook = AddressBook::where('user_id', $owner)->get();
        return response()->json([
            'message' => 'Success',
            'addressBooks' => $addressBook
        ]);
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
    public function show(AddressBook $addressBook,$addressId)
    {
        $user = auth()->user();
        $addressBook = AddressBook::where('id', $addressId)
                                  ->where('user_id', $user->id)
                                  ->first();

        if (!$addressBook) {
            return response()->json(['error' => 'Address not found or unauthorized'], 404);
        }

        return response()->json($addressBook);

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
    public function update(UpdateAddressBookRequest $request, AddressBook $addressBook,$addressId)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $addressBook = AddressBook::where('id', $addressId)
                                    ->where('user_id',$user->id)
                                    ->first();
        if (!$addressBook) {
            return response()->json(['error' => 'Address not found or unauthorized'], 404);
        }
        if($validated['isDefault']){
            $allAddress = AddressBook::where('user_id',$user->id)->update(['isDefault' => false]);

            $addressBook->update($validated);
            return response()->json(['success' => true, 'addressBook' => $addressBook], 200);
        }
        $addressBook->update($validated);
        return response()->json(['success' => true, 'addressBook' => $addressBook]);

    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Set an address as the default address for the user.
     */
    public function setDefaultAddress(Request $request, $addressId)
    {
        $user = auth()->user();

        // Find the address book entry
        $addressBook = AddressBook::where('id', $addressId)
                                  ->where('user_id', $user->id)
                                  ->first();

        // Check if the address exists and belongs to the user
        if (!$addressBook) {
            return response()->json(['error' => 'Address not found or unauthorized'], 404);
        }

        // Set all other addresses as non-default
        AddressBook::where('user_id', $user->id)->update(['isDefault' => false]);

        // Set the selected address as default
        $addressBook->isDefault = true;
        $addressBook->save();

        return response()->json([
            'success' => true,
            'message' => 'Default address updated successfully',
            'addressBook' => $addressBook
        ]);
    }

    public function destroy(Request $request,$addressId)
    {
        //
        $user = auth()->user();

        $addressBook = AddressBook::where('id', $addressId)
                                  ->where('user_id', $user->id)
                                  ->first();


        if (!$addressBook) {
            return response()->json(['error' => 'Address not found or unauthorized'], 404);
        }


        $wasDefault = $addressBook->isDefault;

        $addressBook->delete();


        if ($wasDefault) {
            $newDefault = AddressBook::where('user_id', $user->id)->first();
            if ($newDefault) {
                $newDefault->isDefault = true;
                $newDefault->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }
}
