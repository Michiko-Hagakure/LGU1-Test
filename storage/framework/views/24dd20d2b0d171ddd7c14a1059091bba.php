

<?php $__env->startSection('page-content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-lgu-headline mb-2">Message Templates</h1>
            <p class="text-lgu-paragraph">Manage email, SMS, and in-app message templates</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('admin.templates.trash')); ?>" 
               class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition shadow-lg">
                <i data-lucide="trash-2" class="w-5 h-5 inline mr-2"></i>
                View Trash
            </a>
            <a href="<?php echo e(route('admin.templates.create')); ?>" 
               class="px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition shadow-lg">
                <i data-lucide="plus" class="w-5 h-5 inline mr-2"></i>
                Create Template
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="check-circle" class="w-5 h-5 inline mr-2"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="alert-circle" class="w-5 h-5 inline mr-2"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-2xl shadow-lg mb-6">
        <div class="flex border-b border-gray-200 overflow-x-auto">
            <button class="filter-tab px-6 py-4 font-semibold text-sm whitespace-nowrap transition-colors active" data-filter="all">
                All Templates
            </button>
            <button class="filter-tab px-6 py-4 font-semibold text-sm whitespace-nowrap transition-colors" data-filter="email">
                <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i> Email
            </button>
            <button class="filter-tab px-6 py-4 font-semibold text-sm whitespace-nowrap transition-colors" data-filter="sms">
                <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i> SMS
            </button>
            <button class="filter-tab px-6 py-4 font-semibold text-sm whitespace-nowrap transition-colors" data-filter="in-app">
                <i data-lucide="bell" class="w-4 h-4 inline mr-1"></i> In-App
            </button>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="template-card bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition" 
                 data-type="<?php echo e($template->type); ?>"
                 data-category="<?php echo e($template->category); ?>">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-lgu-headline mb-1"><?php echo e($template->name); ?></h3>
                        <div class="flex gap-2 flex-wrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                <?php echo e($template->type === 'email' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($template->type === 'sms' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($template->type === 'in-app' ? 'bg-purple-100 text-purple-800' : ''); ?>">
                                <?php echo e(strtoupper($template->type)); ?>

                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                <?php echo e(ucfirst($template->category)); ?>

                            </span>
                            <?php if($template->is_active): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i data-lucide="check-circle" class="w-3 h-3 inline"></i> Active
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i data-lucide="x-circle" class="w-3 h-3 inline"></i> Inactive
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Content Preview -->
                <?php if($template->subject): ?>
                    <div class="mb-3">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Subject:</p>
                        <p class="text-sm text-gray-900"><?php echo e($template->subject); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600"><?php echo nl2br(e($template->body)); ?></p>
                </div>

                <!-- Variables -->
                <?php if($template->variables && count(json_decode($template->variables, true)) > 0): ?>
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Available Variables:</p>
                        <div class="flex flex-wrap gap-1">
                            <?php $__currentLoopData = json_decode($template->variables, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <code class="text-xs bg-white px-2 py-1 rounded border border-gray-300">{{{{ $var }}}}</code>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="flex gap-2 border-t pt-4">
                    <button onclick="previewTemplate(<?php echo e($template->id); ?>)" 
                            class="flex-1 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 transition">
                        <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i> Preview
                    </button>
                    <a href="<?php echo e(route('admin.templates.edit', $template->id)); ?>" 
                       class="flex-1 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition text-center">
                        <i data-lucide="edit" class="w-4 h-4 inline mr-1"></i> Edit
                    </a>
                    <form action="<?php echo e(route('admin.templates.toggle', $template->id)); ?>" method="POST" class="inline toggle-form">
                        <?php echo csrf_field(); ?>
                        <button type="button" 
                                class="px-4 py-2 <?php echo e($template->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'); ?> text-white text-sm font-semibold rounded-lg transition"
                                onclick="confirmToggle(this, <?php echo e($template->is_active ? 'true' : 'false'); ?>)">
                            <i data-lucide="<?php echo e($template->is_active ? 'pause' : 'play'); ?>" class="w-4 h-4"></i>
                        </button>
                    </form>
                    <form action="<?php echo e(route('admin.templates.destroy', $template->id)); ?>" method="POST" class="inline delete-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" 
                                class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition"
                                onclick="confirmDelete(this)">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-12">
                <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">No message templates found.</p>
                <a href="<?php echo e(route('admin.templates.create')); ?>" 
                   class="inline-block mt-4 px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition">
                    Create Your First Template
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-lgu-headline">Template Preview</h3>
            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="previewContent"></div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.querySelectorAll('.filter-tab').forEach(button => {
    button.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(btn => {
            btn.classList.remove('active', 'text-lgu-button', 'border-b-2', 'border-lgu-button');
            btn.classList.add('text-gray-600');
        });
        this.classList.add('active', 'text-lgu-button', 'border-b-2', 'border-lgu-button');
        this.classList.remove('text-gray-600');
        
        // Filter cards
        document.querySelectorAll('.template-card').forEach(card => {
            if (filter === 'all' || card.dataset.type === filter) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
});

// Initialize first tab
document.querySelector('.filter-tab.active')?.classList.add('text-lgu-button', 'border-b-2', 'border-lgu-button');

function previewTemplate(id) {
    fetch(`/admin/templates/${id}/preview`)
        .then(response => response.json())
        .then(data => {
            let content = '';
            if (data.type === 'email' && data.subject) {
                content += `<div class="mb-4"><strong class="text-gray-700">Subject:</strong> <p class="text-gray-900 mt-1">${data.subject}</p></div>`;
            }
            content += `<div><strong class="text-gray-700">Message:</strong> <div class="mt-2 p-4 bg-gray-50 rounded-lg text-gray-900 whitespace-pre-wrap">${data.body}</div></div>`;
            
            document.getElementById('previewContent').innerHTML = content;
            document.getElementById('previewModal').classList.remove('hidden');
            lucide.createIcons();
        })
        .catch(error => {
            alert('Failed to load preview: ' + error.message);
        });
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});

function confirmToggle(button, isActive) {
    Swal.fire({
        title: 'Toggle Template Status?',
        text: isActive ? 'This will deactivate the template.' : 'This will activate the template.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isActive ? '#eab308' : '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: isActive ? 'Yes, Deactivate' : 'Yes, Activate',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

function confirmDelete(button) {
    Swal.fire({
        title: 'Delete This Template?',
        text: 'This action cannot be undone. The template will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

lucide.createIcons();
</script>

<style>
.filter-tab {
    border-bottom: 2px solid transparent;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/templates/index.blade.php ENDPATH**/ ?>