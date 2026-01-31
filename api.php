<?php

use Illuminate\Http\Request;
use App\Models\FundRequest;

Route::post('/receive-funds', function (Request $request) {
    // We create the record and include the new logistics data
    $newRequest = FundRequest::create([
        'requester_name' => $request->requester_name, 
        'user_id'        => $request->user_id,
        'amount'         => $request->amount,
        'purpose'        => $request->purpose,
        'logistics'      => $request->logistics, // This captures the chairs, speakers, etc.
        'status'         => 'pending'
    ]);

    if($newRequest) {
        return response()->json([
            'status' => 'success', 
            'id' => $newRequest->id
        ]);
    }
    
    return response()->json(['status' => 'error'], 500);
});

