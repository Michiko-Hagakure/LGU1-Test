<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Trail Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #00473e;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #00473e;
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #475d5b;
            font-size: 11px;
        }
        
        .info-box {
            background-color: #f2f7f5;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-box p {
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background-color: #00473e;
            color: white;
        }
        
        table th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        
        table td {
            padding: 6px 5px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .badge-created {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-updated {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-deleted {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LGU1 Public Facilities</h1>
        <h2 style="color: #00473e; font-size: 16px; margin-top: 5px;">Audit Trail Report</h2>
        <p>Generated on <?php echo e(now()->format('F d, Y - h:i A')); ?></p>
    </div>

    <div class="info-box">
        <p><strong>Total Records:</strong> <?php echo e(count($logs)); ?></p>
        <p><strong>Report Period:</strong> <?php echo e(request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'All Time'); ?> to <?php echo e(request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'Present'); ?></p>
        <?php if(request('event')): ?>
            <p><strong>Action Filter:</strong> <?php echo e(ucfirst(request('event'))); ?></p>
        <?php endif; ?>
        <?php if(request('log_name')): ?>
            <p><strong>Module Filter:</strong> <?php echo e(ucfirst(request('log_name'))); ?></p>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Date/Time</th>
                <th style="width: 15%;">User</th>
                <th style="width: 10%;">Action</th>
                <th style="width: 12%;">Module</th>
                <th style="width: 35%;">Description</th>
                <th style="width: 16%;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($log->created_at->format('M d, Y H:i')); ?></td>
                    <td><?php echo e($log->causer?->name ?? 'System'); ?></td>
                    <td>
                        <?php if($log->event === 'created'): ?>
                            <span class="badge badge-created">Created</span>
                        <?php elseif($log->event === 'updated'): ?>
                            <span class="badge badge-updated">Updated</span>
                        <?php elseif($log->event === 'deleted'): ?>
                            <span class="badge badge-deleted">Deleted</span>
                        <?php else: ?>
                            <?php echo e(ucfirst($log->event ?? 'N/A')); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e(ucfirst($log->log_name ?? 'N/A')); ?></td>
                    <td><?php echo e($log->description); ?></td>
                    <td><?php echo e($log->ip_address ?? 'N/A'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No audit logs found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report from LGU1 Public Facilities Reservation System</p>
        <p>For official use only. Confidential information.</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/audit-trail/pdf.blade.php ENDPATH**/ ?>