@extends('layouts.citizen')

@section('title', 'Inquiry Submitted')
@section('page-title', 'Inquiry Submitted')
@section('page-subtitle', 'Your message has been received')

@section('page-content')
<div class="max-w-2xl mx-auto text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Inquiry Submitted Successfully!</h1>
        
        @if(session('ticket_number'))
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
            <p class="text-sm text-gray-600 mb-2">Your Ticket Number</p>
            <p class="text-2xl font-bold text-blue-600">{{ session('ticket_number') }}</p>
        </div>
        @endif

        <p class="text-gray-600 mb-8">
            Thank you for contacting us! We've received your inquiry and will respond within 24-48 hours on business days.
            Please save your ticket number for future reference.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('citizen.contact.my-inquiries') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                View My Inquiries
            </a>
            <a href="{{ route('citizen.dashboard') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg transition duration-200">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
