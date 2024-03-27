<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = auth()->user()->cards;

        // Return response with user's cards data
        return response()->json([
            'status' => true,
            'message' => 'User cards retrieved successfully',
            'data' => $cards,
        ], 200);
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


        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company' => 'required|string',
            'title' => 'required|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

    
        $card = new card([
            'name' => $request->name,
            'company' => $request->company,
            'title' => $request->title,
            'phone' => $request->phone,
            'users_id' => $user->id,
        ]);
        $card->save();


        return response()->json(['message' => 'Business card created successfully', 'card' => $card]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        // Ensure the authenticated user owns the card
        if (auth()->user()->id !== $card->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Return response with specified card data
        return response()->json([
            'status' => true,
            'message' => 'Card retrieved successfully',
            'data' => $card,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'company' => 'string',
            'title' => 'string',
            'phone' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $businessCard = Card::find($id);

        if (!$businessCard) {
            return response()->json(['message' => 'Business card not found'], 404);
        }

        if ($user->id !== $businessCard->users_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $businessCard->update($request->all());

        return response()->json(['message' => 'Business card updated successfully', 'card' => $businessCard]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $user = Auth::user();

        $businessCard = card::find($id);


        if (!$businessCard) {
            return response()->json(['message' => 'Business card not found'], 404);
        }

      
        $businessCard->delete();

        return response()->json(['message' => 'Business card deleted successfully']);
    }
      
}
