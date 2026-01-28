@extends('layouts.admin')

@section('title', 'Create News Article')
@section('page-title', 'Create News')
@section('page-subtitle', 'Add a new news article')

@section('page-content')
<div class="mb-6">
    <a href="{{ URL::signedRoute('admin.news.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Back to News
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create News Article</h1>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ URL::signedRoute('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Title --}}
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                    URL Slug <span class="text-red-500">*</span>
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                    placeholder="news-url-slug">
                @error('slug')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category" id="category" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                    <option value="">Select Category</option>
                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="facility_update" {{ old('category') == 'facility_update' ? 'selected' : '' }}>Facility Update</option>
                    <option value="policy_change" {{ old('category') == 'policy_change' ? 'selected' : '' }}>Policy Change</option>
                    <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="emergency" {{ old('category') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Published Date --}}
            <div>
                <label for="published_at_display" class="block text-sm font-semibold text-gray-700 mb-2">
                    Publish Date <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="published_at" id="published_at" value="{{ old('published_at') }}" required>
                <div id="published_at_display" onclick="openDatePicker()"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors flex items-center justify-between @error('published_at') border-red-500 @enderror">
                    <span id="published_at_text" class="text-gray-500">Select publish date...</span>
                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                </div>
                @error('published_at')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Excerpt --}}
            <div class="md:col-span-2">
                <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">
                    Excerpt
                </label>
                <textarea name="excerpt" id="excerpt" rows="2" maxlength="500"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('excerpt') border-red-500 @enderror"
                    placeholder="Brief summary of the article (max 500 characters)">{{ old('excerpt') }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Content --}}
            <div class="md:col-span-2">
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                    Content <span class="text-red-500">*</span>
                </label>
                <textarea name="content" id="content" rows="8" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image --}}
            <div class="md:col-span-2">
                <label for="image_path" class="block text-sm font-semibold text-gray-700 mb-2">
                    Featured Image
                </label>
                <input type="file" name="image_path" id="image_path" accept="image/jpeg,image/png,image/jpg"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image_path') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</p>
                @error('image_path')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Options --}}
            <div class="md:col-span-2 flex flex-wrap gap-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Featured Article</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }}
                        class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700">Urgent</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ URL::signedRoute('admin.news.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                Publish Article
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('title').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
});

if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Beautiful Date Picker with SweetAlert2
function openDatePicker() {
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth();
    
    let selectedYear = currentYear;
    let selectedMonth = currentMonth;
    let selectedDay = null;
    
    const existingValue = document.getElementById('published_at').value;
    if (existingValue) {
        const parts = existingValue.split('-');
        selectedYear = parseInt(parts[0]);
        selectedMonth = parseInt(parts[1]) - 1;
        selectedDay = parseInt(parts[2]);
    }
    
    function renderCalendar() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        const firstDay = new Date(selectedYear, selectedMonth, 1).getDay();
        const daysInMonth = new Date(selectedYear, selectedMonth + 1, 0).getDate();
        const prevMonthDays = new Date(selectedYear, selectedMonth, 0).getDate();
        
        let calendarHTML = `
            <div class="swal2-calendar-container">
                <div class="flex items-center justify-between mb-4">
                    <button type="button" onclick="navigateMonth(-1)" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="text-lg font-semibold text-gray-800">${monthNames[selectedMonth]} ${selectedYear}</div>
                    <button type="button" onclick="navigateMonth(1)" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-1 mb-2">
                    ${dayNames.map(d => `<div class="text-center text-xs font-medium text-gray-500 py-2">${d}</div>`).join('')}
                </div>
                <div class="grid grid-cols-7 gap-1">
        `;
        
        for (let i = firstDay - 1; i >= 0; i--) {
            calendarHTML += `<div class="text-center py-2 text-gray-300 text-sm">${prevMonthDays - i}</div>`;
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === today.getDate() && selectedMonth === currentMonth && selectedYear === currentYear;
            const isSelected = day === selectedDay;
            
            let classes = 'text-center py-2 text-sm rounded-lg cursor-pointer transition-all duration-200 ';
            if (isSelected) {
                classes += 'bg-blue-600 text-white font-semibold shadow-md';
            } else if (isToday) {
                classes += 'bg-blue-100 text-blue-600 font-semibold hover:bg-blue-200';
            } else {
                classes += 'hover:bg-gray-100 text-gray-700';
            }
            
            calendarHTML += `<div class="${classes}" onclick="selectDate(${day})">${day}</div>`;
        }
        
        const remainingDays = 42 - (firstDay + daysInMonth);
        for (let i = 1; i <= remainingDays; i++) {
            calendarHTML += `<div class="text-center py-2 text-gray-300 text-sm">${i}</div>`;
        }
        
        calendarHTML += '</div></div>';
        return calendarHTML;
    }
    
    window.navigateMonth = function(direction) {
        selectedMonth += direction;
        if (selectedMonth > 11) {
            selectedMonth = 0;
            selectedYear++;
        } else if (selectedMonth < 0) {
            selectedMonth = 11;
            selectedYear--;
        }
        Swal.update({ html: renderCalendar() });
    };
    
    window.selectDate = function(day) {
        selectedDay = day;
        const formattedDate = `${selectedYear}-${String(selectedMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        document.getElementById('published_at').value = formattedDate;
        
        const displayDate = new Date(selectedYear, selectedMonth, day);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('published_at_text').textContent = displayDate.toLocaleDateString('en-US', options);
        document.getElementById('published_at_text').classList.remove('text-gray-500');
        document.getElementById('published_at_text').classList.add('text-gray-800');
        
        Swal.close();
    };
    
    Swal.fire({
        title: '<span class="text-xl font-bold text-gray-800">Select Publish Date</span>',
        html: renderCalendar(),
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: 'rounded-2xl',
            closeButton: 'text-gray-400 hover:text-gray-600'
        },
        width: '380px',
        padding: '1.5rem'
    });
}
</script>
@endpush
@endsection
