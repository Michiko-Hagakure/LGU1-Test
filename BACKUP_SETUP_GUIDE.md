# 🗄️ Database Backup & Restore Setup Guide

## Overview

The LGU1 Public Facilities Reservation System includes an automated database backup solution using **Spatie Laravel Backup** package. This guide covers setup, configuration, and daily operations.

---

## ✅ Features Implemented

### Manual Backup Operations (Admin Portal)
- **Create Backup Now** - On-demand database backup
- **Download Backups** - Download backup files for off-site storage
- **Delete Backups** - Remove old or unnecessary backups
- **Clean Old Backups** - Apply 30-day retention policy
- **View Backup List** - See all available backups with size and date

### What Gets Backed Up
- ✅ **lgu1_facilities** database (bookings, facilities, equipment, payments)
- ✅ **lgu1_auth** database (users, roles, system settings, audit logs)

### Retention Policy
- **Keep all backups for:** 7 days
- **Keep daily backups for:** 30 days
- **Total retention:** 30 days
- **Maximum storage:** 5000 MB (5 GB)

---

## 🚀 Quick Start

### Access the Backup Portal
1. Login as **Admin**
2. Navigate to **System → Backup & Restore**
3. Click **"Create Backup Now"** to create your first backup
4. Backup will be stored in: `storage/app/LGU1 Facilities Reservation System/`

### Test Your First Backup
```bash
# Run manual backup via command line
php artisan backup:run --only-db

# Expected output:
# Starting backup...
# Dumping database lgu1_facilities...
# Dumping database lgu1_auth...
# Backup completed!
```

---

## ⚙️ Configuration

### Backup Settings (`config/backup.php`)

**Databases to backup:**
```php
'databases' => [
    'facilities_db',  // lgu1_facilities
    'auth_db',        // lgu1_auth
],
```

**Storage location:**
```php
'destination' => [
    'disks' => ['local'],  // storage/app/
],
```

**Retention policy:**
```php
'keep_all_backups_for_days' => 7,
'keep_daily_backups_for_days' => 30,
```

### MySQL Dump Configuration (`config/database.php`)

Both database connections include dump configuration:
```php
'dump' => [
    'dump_binary_path' => env('DB_DUMP_PATH', 'C:/laragon/bin/mysql/mysql-8.0.42-winx64/bin'),
    'use_single_transaction' => true,
    'timeout' => 60 * 5,
],
```

**For different MySQL versions or paths, update `.env`:**
```env
DB_DUMP_PATH=C:/laragon/bin/mysql/mysql-8.0.XX-winx64/bin
```

---

## 🕐 Automated Daily Backups

### Option 1: Windows Task Scheduler (Recommended for Windows)

#### Step 1: Create Backup Script
Create `backup-daily.bat` in project root:
```batch
@echo off
cd C:\laragon\www\local-government-unit-1-ph.com
php artisan backup:run --only-db
```

#### Step 2: Configure Task Scheduler
1. Open **Task Scheduler** (Win + R → `taskschd.msc`)
2. Click **Create Basic Task**
3. **Name:** LGU1 Daily Database Backup
4. **Trigger:** Daily at 2:00 AM
5. **Action:** Start a program
   - **Program:** `C:\laragon\www\local-government-unit-1-ph.com\backup-daily.bat`
6. **Settings:**
   - ✅ Run whether user is logged on or not
   - ✅ Run with highest privileges
7. Click **Finish**

#### Step 3: Test the Task
```powershell
# Test run immediately
schtasks /run /tn "LGU1 Daily Database Backup"
```

---

### Option 2: Laravel Task Scheduler (Alternative)

#### Step 1: Add to `app/Console/Kernel.php`
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('backup:run --only-db')
             ->daily()
             ->at('02:00');
             
    $schedule->command('backup:clean')
             ->daily()
             ->at('03:00');
}
```

#### Step 2: Add Cron Job (Linux) or Task Scheduler (Windows)

**Windows Task Scheduler:**
```batch
cd C:\laragon\www\local-government-unit-1-ph.com
php artisan schedule:run
```
- Run every minute

**Linux Cron:**
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📥 Backup Operations

### Create Manual Backup
```bash
# Database only (recommended)
php artisan backup:run --only-db

# Full backup (database + files - slower)
php artisan backup:run
```

### List Backups
```bash
php artisan backup:list
```

### Clean Old Backups (30-day retention)
```bash
php artisan backup:clean
```

### Check Backup Health
```bash
php artisan backup:monitor
```

---

## 🔄 Restore from Backup

### Important: Restoration Steps

1. **Download the backup file** from Admin Portal or directly from:
   ```
   storage/app/LGU1 Facilities Reservation System/
   ```

2. **Extract the ZIP file** to get the `.sql` files:
   - `database-lgu1_facilities.sql`
   - `database-lgu1_auth.sql`

3. **Restore via phpMyAdmin:**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Select database `lgu1_facilities`
   - Click **Import** → Choose `database-lgu1_facilities.sql`
   - Click **Go**
   - Repeat for `lgu1_auth` database

4. **Restore via Command Line:**
   ```bash
   # Restore facilities database
   mysql -u root -p lgu1_facilities < database-lgu1_facilities.sql
   
   # Restore auth database
   mysql -u root -p lgu1_auth < database-lgu1_auth.sql
   ```

5. **Verify restoration:**
   - Check booking records
   - Check user accounts
   - Test login functionality
   - Check system settings

---

## 🛡️ Best Practices

### Daily Operations
- ✅ Verify backup runs successfully every day
- ✅ Check backup file size (should be consistent)
- ✅ Monitor available disk space

### Weekly Tasks
- ✅ Download a backup file for off-site storage
- ✅ Test restore process on staging environment
- ✅ Review backup logs for errors

### Monthly Review
- ✅ Verify retention policy is working
- ✅ Check total backup storage usage
- ✅ Update backup documentation if needed

### Off-Site Storage (Highly Recommended)
- Upload backups to cloud storage (Google Drive, Dropbox, AWS S3)
- Store backups on external hard drive
- Keep 3 copies: local, external, cloud (3-2-1 rule)

---

## 📊 Storage Management

### Backup File Sizes (Approximate)
- **lgu1_facilities:** 20-50 KB (small database)
- **lgu1_auth:** 10-30 KB (user database)
- **Total per backup:** ~50-100 KB

### 30-Day Storage Calculation
- Daily backups: 30 files
- Expected storage: 1.5 - 3 MB
- Maximum allowed: 5000 MB (5 GB)

### Manual Cleanup
If storage gets full:
1. Go to Admin → Backup & Restore
2. Click **"Clean Old Backups"** (applies 30-day policy)
3. Or manually delete old backups you don't need

---

## 🚨 Troubleshooting

### Error: "mysqldump is not recognized"

**Solution:** Configure mysqldump path in `.env`:
```env
DB_DUMP_PATH=C:/laragon/bin/mysql/mysql-8.0.42-winx64/bin
```

### Error: "The dump process failed"

**Causes:**
- MySQL not running
- Incorrect database credentials
- Insufficient permissions

**Solution:**
1. Verify MySQL is running (Laragon → Start All)
2. Check database credentials in `.env`
3. Test connection: `php artisan tinker` → `DB::connection('auth_db')->getPdo()`

### Backup File Not Appearing

**Check:**
1. Storage directory exists: `storage/app/LGU1 Facilities Reservation System/`
2. Directory permissions (Windows: Full Control)
3. Laravel logs: `storage/logs/laravel.log`

### Automated Backup Not Running

**Windows Task Scheduler:**
1. Check task history for errors
2. Verify batch file path is correct
3. Ensure "Run with highest privileges" is enabled
4. Test batch file manually first

---

## 📋 Backup Checklist

### Initial Setup
- [ ] Composer package installed (`spatie/laravel-backup`)
- [ ] Configuration published (`php artisan vendor:publish`)
- [ ] MySQL dump path configured in `.env`
- [ ] Test manual backup successful
- [ ] Automated task scheduler configured
- [ ] First backup downloaded and stored off-site

### Daily Verification
- [ ] Check backup ran successfully (Admin Portal)
- [ ] Verify backup file size is reasonable
- [ ] Check no errors in Laravel logs

### Monthly Maintenance
- [ ] Test backup restoration on staging
- [ ] Download backup for off-site storage
- [ ] Review retention policy and storage usage
- [ ] Update documentation if process changed

---

## 🔐 Security Considerations

### Backup File Security
- Backup files contain sensitive data (user info, passwords)
- Store backups in secure locations only
- Encrypt backups for cloud storage
- Use strong passwords for backup archives

### Access Control
- Only Admin role can access backup features
- Log all backup operations (audit trail)
- Monitor backup download activities

### Compliance
- Follow data retention policies
- Document backup procedures
- Test disaster recovery plan quarterly

---

## 📞 Support

### Need Help?
- Check Laravel logs: `storage/logs/laravel.log`
- Review backup package docs: https://spatie.be/docs/laravel-backup
- Contact system administrator or lead programmer

### Report Issues
- Include error messages from logs
- Specify backup file name and timestamp
- Note system environment (Laragon version, MySQL version)

---

## 📝 Version History

**v1.2 (January 2026)**
- Initial implementation
- Database-only backups
- 30-day retention policy
- Admin portal interface
- Automated daily backups support

---

**Last Updated:** January 2026  
**Maintained By:** LGU1 Development Team
