<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'LGU1 Public Facilities'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <!-- Poppins Font (PROJECT_DESIGN_RULES.md requirement) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- ApexCharts for dashboard graphs (per ARCHITECTURE.md) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Lucide Icons (PROJECT_DESIGN_RULES.md requirement) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="h-full">
    <div class="min-h-full">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    <!-- 2-MINUTE SESSION TIMEOUT (CRITICAL FOR DEFENSE - PROJECT_DESIGN_RULES.md) -->
    <!-- Silent logout after 2 minutes of inactivity - no warnings, no modals -->
    <!-- Only runs when user is authenticated (custom session check) -->
    <?php if(session('user_id')): ?>
    <script>
        (function() {
            // Extra safety: Don't run on auth pages (login, register, password reset)
            const currentPath = window.location.pathname;
            if (currentPath.includes('/login') || currentPath.includes('/register') || currentPath.includes('/password')) {
                return; // Exit immediately
            }

            let sessionTimeout;
            const SESSION_DURATION = <?php echo e(config('session.lifetime')); ?> * 60 * 1000; // Session timeout in milliseconds

            function resetSessionTimer() {
                clearTimeout(sessionTimeout);
                
                // Silent logout after session timeout
                sessionTimeout = setTimeout(() => {
                    // Create a form and submit POST request to logout
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/logout';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.content;
                        form.appendChild(csrfInput);
                    }
                    
                    document.body.appendChild(form);
                    form.submit();
                }, SESSION_DURATION);
            }

            ['click', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, resetSessionTimer);
            });

            document.addEventListener('DOMContentLoaded', resetSessionTimer);
        })();
    </script>
    <?php endif; ?>
</body>
</html>

<?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\local-government-unit-1-ph.com\resources\views/layouts/master.blade.php ENDPATH**/ ?>