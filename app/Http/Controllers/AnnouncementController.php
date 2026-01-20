<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function dismiss(Request $request)
    {
        $request->session()->put('announcement_dismissed', true);
        
        return response()->json(['success' => true]);
    }
}
