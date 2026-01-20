<?php
    use App\Models\SystemSetting;
    $announcementSetting = SystemSetting::where('key', 'system.announcement')->first();
    $announcement = $announcementSetting ? $announcementSetting->getTypedValue() : '';
    $announcementImage = $announcementSetting && $announcementSetting->announcement_image ? asset($announcementSetting->announcement_image) : null;
    $isDismissed = session('announcement_dismissed', false);
?>

<?php if(!empty($announcement) && !$isDismissed): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show announcement as SweetAlert2 modal
    Swal.fire({
        icon: 'info',
        title: '<strong style="color: #047857;">System Announcement</strong>',
        html: `
            <div style="text-align: left; padding: 20px; line-height: 1.6;">
                <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <i class="fas fa-bullhorn" style="color: #F59E0B; margin-right: 10px;"></i>
                    <strong style="color: #92400E;">Important Notice</strong>
                </div>
                <?php if($announcementImage): ?>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="<?php echo e($announcementImage); ?>" 
                             alt="Announcement" 
                             style="max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    </div>
                <?php endif; ?>
                <p style="color: #374151; font-size: 16px; margin: 0;"><?php echo e(addslashes($announcement)); ?></p>
            </div>
        `,
        showCloseButton: true,
        showCancelButton: false,
        confirmButtonText: 'Got it!',
        confirmButtonColor: '#047857',
        allowOutsideClick: false,
        allowEscapeKey: true,
        customClass: {
            container: 'announcement-modal',
            popup: 'announcement-popup',
            title: 'announcement-title',
            htmlContainer: 'announcement-content'
        },
        width: '600px',
        padding: '2em',
        backdrop: 'rgba(0, 0, 0, 0.7)',
        didOpen: () => {
            // Add custom animation
            const popup = Swal.getPopup();
            popup.style.animation = 'slideInDown 0.5s ease-out';
        },
        willClose: () => {
            // Add closing animation
            const popup = Swal.getPopup();
            popup.style.animation = 'fadeOut 0.3s ease-out';
            
            // Send dismissal request when modal is closing (works for both button and X)
            fetch('<?php echo e(route('announcement.dismiss')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            }).catch(error => {
                console.error('Error dismissing announcement:', error);
            });
        }
    });
});
</script>

<style>
@keyframes slideInDown {
    from {
        transform: translate3d(0, -100%, 0);
        opacity: 0;
    }
    to {
        transform: translate3d(0, 0, 0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.9);
    }
}

.announcement-popup {
    border-top: 5px solid #047857 !important;
}

.announcement-title {
    font-family: 'Poppins', sans-serif !important;
    font-size: 24px !important;
}

.announcement-content {
    font-family: 'Poppins', sans-serif !important;
}
</style>
<?php endif; ?>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/components/announcement-banner.blade.php ENDPATH**/ ?>