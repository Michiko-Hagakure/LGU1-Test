

<?php $__env->startSection('page-title', 'System Settings'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container-fluid py-5" style="background-color: #f8fafb; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            
            <div class="mb-5 border-bottom pb-4">
                <h1 class="display-6 fw-bold mb-2" style="color: #064e3b; letter-spacing: -1px;">System Configuration</h1>
                <p class="fs-5 text-muted">Manage administrative credentials and official government unit settings.</p>
            </div>

            
            <?php if(session('success')): ?>
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center p-4 mb-4" role="alert" style="border-radius: 15px; background-color: #d1fae5; color: #065f46;">
                    <i class="fas fa-check-circle me-3 fs-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Success!</h6>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger border-0 shadow-sm p-4 mb-4" role="alert" style="border-radius: 15px; background-color: #fee2e2; color: #991b1b;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-exclamation-triangle me-3 fs-3"></i>
                        <h6 class="fw-bold mb-0">Update Failed!</h6>
                    </div>
                    <ul class="mb-0 small ps-4">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-lg mt-2" style="border-radius: 25px; overflow: hidden;">
                <div class="row g-0">
                    
                    <div class="col-md-3 bg-white border-end">
                        <div class="nav flex-column nav-pills p-4" id="v-pills-tab" role="tablist">
                            <button class="nav-link active sidebar-link mb-3 py-3 shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button">
                                <i class="fas fa-user-shield me-3"></i> Admin Profile
                            </button>
                            <button class="nav-link sidebar-link mb-3 py-3 shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-lgu" type="button">
                                <i class="fas fa-university me-3"></i> LGU Configuration
                            </button>
                            <button class="nav-link sidebar-link py-3 shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-security" type="button">
                                <i class="fas fa-lock me-3"></i> Password & Security
                            </button>
                        </div>
                    </div>

                    <div class="col-md-9 bg-white">
                        <div class="tab-content p-5">
                            
                            <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                                <form action="/admin/settings/profile" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <h3 class="fw-bold mb-5" style="color: #064e3b;">Personal Identification</h3>
                                    
                                    <div class="d-flex align-items-center mb-5 p-4 rounded-4" style="background-color: #f1f5f9;">
                                        <div class="position-relative">
                                            <?php 
                                                $user = Auth::user();
                                                $photo = ($user && $user->profile_photo_path) ? asset($user->profile_photo_path) : 'https://ui-avatars.com/api/?name=Admin&background=064e3b&color=fff&size=200';
                                            ?>
                                            <img id="avatar-preview" src="<?php echo e($photo); ?>" 
                                                 class="rounded-circle border border-5 border-white shadow"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                            
                                            <label for="avatar_input" class="btn btn-success position-absolute bottom-0 end-0 rounded-circle shadow p-0 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background-color: #064e3b; border: 3px solid #fff; cursor: pointer;">
                                                <i class="fas fa-camera text-white"></i>
                                            </label>
                                            <input type="file" id="avatar_input" name="avatar" class="d-none" onchange="previewImage(this)">
                                        </div>
                                        <div class="ms-4">
                                            <h5 class="fw-bold mb-1 text-dark">Profile Photo</h5>
                                            <p class="text-muted mb-0">Accepted formats: JPG, PNG. Max 2MB.</p>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-muted text-uppercase small">Full Name</label>
                                            <input type="text" name="full_name" class="form-control huge-input py-3" value="<?php echo e(optional($user)->full_name); ?>" required>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label fw-bold text-muted text-uppercase small">Email Address (Locked)</label>
                                            <input type="email"
                                            name="email"
                                            class="form-control huge-input py-3"
                                            value="<?php echo e(optional($user)->email); ?>"
                                            style="background-color: #e9ecef; cursor: not-allowed;"
                                            readonly>
                                            <small class="text-muted">Contact the Super Admin to change your official email.</small>
                                        </div>
                                        <div class="col-12 mt-4 text-start">
                                            <button type="submit" class="btn btn-success btn-huge px-5 py-3">Update Profile Information</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="tab-lgu" role="tabpanel">
                                <form action="<?php echo e(route('admin.settings.lgu.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <h3 class="fw-bold mb-5" style="color: #064e3b;">Government Unit Credentials</h3>
                                    <div class="row g-4">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold text-muted small text-uppercase">LGU Name</label>
                                            <input type="text" name="lgu_name" class="form-control huge-input" value="<?php echo e($settings['lgu_name'] ?? ''); ?>" placeholder="e.g. Quezon City Government">
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label fw-bold text-muted small text-uppercase">Office Unit</label>
                                            <input type="text" name="office_unit" class="form-control huge-input" value="<?php echo e($settings['office_unit'] ?? ''); ?>" placeholder="e.g. Mayor's Office">
                                        </div>
                                        <div class="col-12 mt-4 text-start">
                                            <button type="submit" class="btn btn-success btn-huge px-5 py-3">Save System Configurations</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. SIDEBAR BUTTON STYLING */
    .sidebar-link {
        text-align: left !important;
        color: #4b5563 !important;
        font-weight: 600 !important;
        border-radius: 15px !important;
        transition: all 0.3s ease;
        border: 1px solid transparent !important;
    }
    .sidebar-link.active {
        background-color: #eaf3f0 !important;
        color: #064e3b !important;
        border: 1px solid #d1fae5 !important;
    }
    .sidebar-link:hover:not(.active) {
        background-color: #f3f4f6 !important;
        color: #064e3b !important;
    }

    /* 2. FORM INPUT STYLING (Modern & Large) */
    .huge-input {
        border-radius: 15px !important;
        border: 2px solid #e5e7eb !important;
        padding: 16px 20px !important;
        background-color: #f9fafb !important;
        font-size: 1.1rem !important;
    }
    .huge-input:focus {
        border-color: #064e3b !important;
        box-shadow: 0 0 0 5px rgba(6, 78, 59, 0.1) !important;
        background-color: #ffffff !important;
    }

    /* 3. PRIMARY ACTION BUTTON STYLING */
    .btn-huge {
        background-color: #064e3b !important;
        border: none !important;
        border-radius: 15px !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        color: white !important;
        box-shadow: 0 10px 15px -3px rgba(6, 78, 59, 0.2);
        transition: all 0.2s ease;
    }
    .btn-huge:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 20px -3px rgba(6, 78, 59, 0.3);
    }

    /* Tab Layout Display Fix */
    .tab-pane { display: none; }
    .tab-pane.active { display: block !important; }
</style>

<script>
    /**
     * JavaScript for Instant Image Preview
     * Updates the avatar circle as soon as a user selects a file.
     */
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\local-government-unit-1-ph.com\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>