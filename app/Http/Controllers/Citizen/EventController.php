<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::published()->orderBy('event_date', 'desc');

        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->paginate(12);
        $featuredEvents = Event::published()->featured()->limit(3)->get();

        return view('citizen.events.index', compact('events', 'featuredEvents'));
    }

    public function show($slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        $event->incrementViewCount();
        
        $relatedEvents = Event::published()
            ->byCategory($event->category)
            ->where('id', '!=', $event->id)
            ->limit(3)
            ->get();

        return view('citizen.events.show', compact('event', 'relatedEvents'));
    }
}
