@extends('layouts.admin')

@section('title', 'Database Backup')
@section('page-title', 'Database Backup & Restore')
@section('page-subtitle', 'Manage system backups for data protection')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-lgu-headline mb-2">Database Backup & Restore</h1>
                <p class="text-lgu-body">Manage system backups for data protection and disaster recovery</p>
            </div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('admin.backup.clean') }}" class="inline" id="cleanBackupForm">
                    @csrf
                    <button type="button" 
                            class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition shadow-md flex items-center gap-2"
                            onclick="confirmCleanBackup()">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                        Clean Old Backups
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.backup.create') }}" class="inline" id="createBackupForm">
                    @csrf
                    <button type="button" 
                            class="px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition shadow-md flex items-center gap-2"
                            onclick="confirmCreateBackup()">
                        <i data-lucide="database" class="w-5 h-5"></i>
                        Create Backup Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                <p class="text-green-700 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3"></i>
                <p class="text-red-700 font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-8">
        <div class="flex items-start">
            <i data-lucide="info" class="w-6 h-6 text-blue-500 mr-4 mt-1 flex-shrink-0"></i>
            <div>
                <h3 class="text-lg font-bold text-blue-900 mb-2">Backup Information</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li><strong>Retention Policy:</strong> Backups are kept for 30 days</li>
                    <li><strong>What's Backed Up:</strong> All databases (facilities_db, auth_db)</li>
                    <li><strong>Storage Location:</strong> {{ storage_path('app/' . config('backup.backup.name')) }}</li>
                    <li><strong>Automated Backups:</strong> Configure daily backups via task scheduler (see documentation)</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg mb-8">
        <div class="flex items-start">
            <i data-lucide="shield-check" class="w-6 h-6 text-green-500 mr-4 mt-1 flex-shrink-0"></i>
            <div>
                <h3 class="text-lg font-bold text-green-900 mb-2">Enhanced Security: OTP-Protected Downloads</h3>
                <p class="text-sm text-green-800 mb-3">All backup downloads are protected with password encryption and OTP verification for maximum security.</p>
                <div class="bg-white/50 p-4 rounded border border-green-200">
                    <p class="text-sm text-green-900 font-semibold mb-2">How it works:</p>
                    <ol class="text-sm text-green-800 space-y-2 list-decimal list-inside">
                        <li>Click the <strong>Download</strong> button for any backup file</li>
                        <li>A unique 6-digit OTP will be generated and sent to your email</li>
                        <li>The backup file will download automatically (password-protected)</li>
                        <li>Check your email for the OTP password (valid for 15 minutes)</li>
                        <li>Extract the backup ZIP file using the OTP as the password</li>
                    </ol>
                    <p class="text-xs text-green-700 mt-3 italic">Security Note: Each download generates a unique OTP. Never share your OTP with anyone.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-lgu-header border-b-4 border-lgu-stroke">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i data-lucide="archive" class="w-5 h-5"></i>
                Available Backups ({{ count($backups) }})
            </h2>
        </div>

        @if(count($backups) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-lgu-headline uppercase tracking-wider">
                                Backup File
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-lgu-headline uppercase tracking-wider">
                                Size
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-lgu-headline uppercase tracking-wider">
                                Created Date
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-lgu-headline uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($backups as $backup)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="file-archive" class="w-5 h-5 text-lgu-accent"></i>
                                        <span class="font-mono text-sm text-gray-700">{{ $backup['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 font-semibold">{{ $backup['size'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $backup['date'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.backup.download', $backup['name']) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-lgu-button text-white text-sm font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-2">
                                                <i data-lucide="download" class="w-4 h-4"></i>
                                                Download
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.backup.destroy', $backup['name']) }}" class="inline delete-backup-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition flex items-center gap-2"
                                                    onclick="confirmDeleteBackup(this, '{{ $backup['name'] }}')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                <p class="text-gray-500 text-lg font-semibold mb-2">No backups available</p>
                <p class="text-gray-400 text-sm mb-6">Create your first backup to get started</p>
                <form method="POST" action="{{ route('admin.backup.create') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition shadow-md flex items-center gap-2 mx-auto">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        Create First Backup
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600 mr-4 mt-1 flex-shrink-0"></i>
            <div>
                <h3 class="text-lg font-bold text-yellow-900 mb-2">Important Notes</h3>
                <ul class="text-sm text-yellow-800 space-y-2">
                    <li><strong>Restoration:</strong> To restore from a backup, download the file and contact your system administrator or lead programmer</li>
                    <li><strong>Off-site Storage:</strong> Consider copying critical backups to external storage or cloud storage for additional safety</li>
                    <li><strong>Regular Testing:</strong> Periodically test backup restoration to ensure data integrity</li>
                    <li><strong>Automated Backups:</strong> Set up Windows Task Scheduler or cron job to run: <code class="bg-yellow-100 px-2 py-1 rounded">php artisan backup:run --only-db</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCreateBackup() {
    Swal.fire({
        title: 'Create Database Backup?',
        html: 'This may take a few minutes.<br><br>The backup will include:<br>• <strong>facilities_db</strong> (bookings, facilities, payments)<br>• <strong>auth_db</strong> (users, roles, settings)',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0E7490',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Create Backup',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        preConfirm: () => {
            document.getElementById('createBackupForm').submit();
            return false;
        }
    });
}

function confirmCleanBackup() {
    Swal.fire({
        title: 'Clean Old Backups?',
        html: 'Clean old backups based on retention policy (30 days)?<br><br>This will remove backups older than 30 days while keeping:<br>• All backups from the last 7 days<br>• Daily backups for 30 days',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EAB308',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Clean Now',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cleanBackupForm').submit();
        }
    });
}

function confirmDeleteBackup(button, fileName) {
    Swal.fire({
        title: 'Delete This Backup?',
        html: `<strong>File:</strong> ${fileName}<br><br>This action cannot be undone. The backup file will be permanently deleted from the server.`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Delete It',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endsection
