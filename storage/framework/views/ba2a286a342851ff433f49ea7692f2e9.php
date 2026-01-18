

<?php $__env->startSection('page-title', 'Settings'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
        <p class="text-sm text-gray-500">Manage LGU configurations and payment methods.</p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-lg">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row min-h-[500px]">
        <aside class="w-full md:w-64 bg-gray-50 border-r border-gray-100 p-4 rounded-l-xl">
            <nav class="space-y-1">
                <button onclick="switchTab('general')" id="btn-general" class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-emerald-50 text-emerald-700">
                    <i class="fas fa-university mr-3"></i> LGU Profile
                </button>
                <button onclick="switchTab('payments')" id="btn-payments" class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-credit-card mr-3"></i> Payments
                </button>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <form action="<?php echo e(route('admin.settings.lgu.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div id="tab-general" class="tab-content space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">LGU Profile</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">LGU Name</label>
                            <input type="text" name="lgu_name" value="<?php echo e($settings->lgu_name ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Official Email</label>
                            <input type="email" name="email" value="<?php echo e($settings->email ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" name="phone" value="<?php echo e($settings->phone ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <div id="tab-payments" class="tab-content hidden space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Payment Details</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">GCash Account Name</label>
                            <input type="text" name="gcash_name" value="<?php echo e($settings->gcash_name ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">GCash Number</label>
                            <input type="text" name="gcash_no" value="<?php echo e($settings->gcash_no ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show selected content
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Reset button styles
        document.querySelectorAll('aside button').forEach(btn => {
            btn.classList.remove('bg-emerald-50', 'text-emerald-700');
            btn.classList.add('text-gray-600');
        });
        // Highlight active button
        document.getElementById('btn-' + tabName).classList.add('bg-emerald-50', 'text-emerald-700');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\local-government-unit-1-ph.com\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>