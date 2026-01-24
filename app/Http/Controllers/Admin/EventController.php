<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'registration_link' => 'nullable|url|max:500',
            'max_attendees' => 'nullable|integer|min:1',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('events', 'public');
        }

        $validated['created_by'] = session('user_id');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $id,
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'registration_link' => 'nullable|url|max:500',
            'max_attendees' => 'nullable|integer|min:1',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('events', 'public');
        }

        $validated['updated_by'] = session('user_id');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->deleted_by = session('user_id');
        $event->save();
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function trash()
    {
        $events = Event::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
        
        return view('admin.events.trash', compact('events'));
    }

    public function restore($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();

        return redirect()->route('admin.events.trash')
            ->with('success', 'Event restored successfully!');
    }
}
