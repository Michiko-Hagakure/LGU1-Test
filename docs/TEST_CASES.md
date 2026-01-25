# LGU1 Facilities Reservation System - Test Cases

**Document Version:** 1.0  
**Last Updated:** January 24, 2026  
**Tester:** [Name]  

---

## 1. Authentication Module

### TC-AUTH-001: User Registration
| Field | Value |
|-------|-------|
| **Objective** | Verify new user can register successfully |
| **Preconditions** | User has valid email and government ID |
| **Test Steps** | 1. Navigate to /register<br>2. Fill in all required fields<br>3. Upload government ID (front and back)<br>4. Take selfie for AI verification<br>5. Accept terms and privacy policy<br>6. Click Register<br>7. Enter OTP from email |
| **Expected Result** | Account created, redirected to dashboard |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-AUTH-002: User Login with OTP
| Field | Value |
|-------|-------|
| **Objective** | Verify user can login with email and OTP |
| **Preconditions** | User has verified account |
| **Test Steps** | 1. Navigate to /login<br>2. Enter email<br>3. Enter password<br>4. Click Login<br>5. Enter OTP from email<br>6. Click Verify |
| **Expected Result** | User logged in, redirected to role-based dashboard |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-AUTH-003: Invalid Login Attempt
| Field | Value |
|-------|-------|
| **Objective** | Verify system rejects invalid credentials |
| **Preconditions** | None |
| **Test Steps** | 1. Navigate to /login<br>2. Enter invalid email/password<br>3. Click Login |
| **Expected Result** | Error message displayed, no login |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-AUTH-004: Session Timeout
| Field | Value |
|-------|-------|
| **Objective** | Verify session expires after inactivity |
| **Preconditions** | User is logged in |
| **Test Steps** | 1. Login to system<br>2. Wait 30+ minutes without activity<br>3. Try to navigate to protected page |
| **Expected Result** | Redirected to login with timeout message |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-AUTH-005: Password Reset
| Field | Value |
|-------|-------|
| **Objective** | Verify user can reset password |
| **Preconditions** | User has verified account |
| **Test Steps** | 1. Click "Forgot Password"<br>2. Enter email<br>3. Enter OTP from email<br>4. Enter new password<br>5. Confirm password<br>6. Click Reset |
| **Expected Result** | Password changed, can login with new password |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## 2. Booking Module

### TC-BOOK-001: Create New Booking
| Field | Value |
|-------|-------|
| **Objective** | Verify citizen can create a facility booking |
| **Preconditions** | User logged in as Citizen |
| **Test Steps** | 1. Navigate to Browse Facilities<br>2. Select a facility<br>3. Click "Book Now"<br>4. Select date and time slot<br>5. Enter event details<br>6. Review booking summary<br>7. Submit booking |
| **Expected Result** | Booking created with "Pending" status |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-BOOK-002: View My Reservations
| Field | Value |
|-------|-------|
| **Objective** | Verify citizen can view their bookings |
| **Preconditions** | User has at least one booking |
| **Test Steps** | 1. Navigate to My Reservations<br>2. View list of bookings<br>3. Click on a booking to view details |
| **Expected Result** | All user bookings displayed with correct status |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-BOOK-003: Cancel Booking
| Field | Value |
|-------|-------|
| **Objective** | Verify citizen can cancel a pending booking |
| **Preconditions** | User has a pending booking |
| **Test Steps** | 1. Navigate to My Reservations<br>2. Select pending booking<br>3. Click Cancel<br>4. Confirm cancellation |
| **Expected Result** | Booking status changed to "Cancelled" |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-BOOK-004: Booking Conflict Detection
| Field | Value |
|-------|-------|
| **Objective** | Verify system prevents double booking |
| **Preconditions** | Facility has existing approved booking |
| **Test Steps** | 1. Try to book same facility<br>2. Select same date/time as existing booking<br>3. Submit booking |
| **Expected Result** | System shows conflict warning or prevents submission |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-BOOK-005: Staff Booking Approval
| Field | Value |
|-------|-------|
| **Objective** | Verify staff can approve bookings |
| **Preconditions** | Staff logged in, pending booking exists |
| **Test Steps** | 1. Navigate to Booking Verification<br>2. Select pending booking<br>3. Review details<br>4. Click Approve |
| **Expected Result** | Booking status changed to "Approved" |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## 3. Payment Module

### TC-PAY-001: Upload Payment Slip
| Field | Value |
|-------|-------|
| **Objective** | Verify citizen can upload payment proof |
| **Preconditions** | User has approved booking requiring payment |
| **Test Steps** | 1. Navigate to booking details<br>2. Click "Upload Payment"<br>3. Select payment image<br>4. Enter reference number<br>5. Submit |
| **Expected Result** | Payment slip uploaded, status "Pending Verification" |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-PAY-002: Treasurer Payment Verification
| Field | Value |
|-------|-------|
| **Objective** | Verify treasurer can verify payments |
| **Preconditions** | Treasurer logged in, payment pending verification |
| **Test Steps** | 1. Navigate to Payment Verification<br>2. Select pending payment<br>3. Review payment slip<br>4. Enter OR number<br>5. Click Verify |
| **Expected Result** | Payment verified, OR generated |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-PAY-003: Generate Official Receipt
| Field | Value |
|-------|-------|
| **Objective** | Verify OR is generated after payment verification |
| **Preconditions** | Payment has been verified |
| **Test Steps** | 1. Navigate to verified payment<br>2. Click "View Receipt"<br>3. Download/Print receipt |
| **Expected Result** | OR displayed with correct details, downloadable |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## 4. Facility Management Module

### TC-FAC-001: Add New Facility
| Field | Value |
|-------|-------|
| **Objective** | Verify admin can add new facility |
| **Preconditions** | Admin logged in |
| **Test Steps** | 1. Navigate to Facilities<br>2. Click "Add Facility"<br>3. Fill in all details<br>4. Upload photos<br>5. Set pricing<br>6. Save |
| **Expected Result** | Facility created and visible in list |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-FAC-002: Edit Facility
| Field | Value |
|-------|-------|
| **Objective** | Verify admin can edit facility details |
| **Preconditions** | Admin logged in, facility exists |
| **Test Steps** | 1. Navigate to Facilities<br>2. Select facility<br>3. Click Edit<br>4. Modify details<br>5. Save |
| **Expected Result** | Changes saved and reflected |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-FAC-003: Facility Availability Toggle
| Field | Value |
|-------|-------|
| **Objective** | Verify admin can toggle facility availability |
| **Preconditions** | Admin logged in, facility exists |
| **Test Steps** | 1. Navigate to Facilities<br>2. Toggle availability switch |
| **Expected Result** | Facility hidden/shown from citizen view |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## 5. Security Module

### TC-SEC-001: CSRF Protection
| Field | Value |
|-------|-------|
| **Objective** | Verify CSRF tokens are validated |
| **Preconditions** | User logged in |
| **Test Steps** | 1. Inspect form HTML<br>2. Remove/modify CSRF token<br>3. Submit form |
| **Expected Result** | Request rejected with 419 error |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-SEC-002: Role-Based Access Control
| Field | Value |
|-------|-------|
| **Objective** | Verify users cannot access unauthorized pages |
| **Preconditions** | Citizen logged in |
| **Test Steps** | 1. Login as Citizen<br>2. Try to access /admin/dashboard<br>3. Try to access /treasurer/payments |
| **Expected Result** | Access denied, redirected to own dashboard |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-SEC-003: Audit Trail Logging
| Field | Value |
|-------|-------|
| **Objective** | Verify actions are logged in audit trail |
| **Preconditions** | Admin logged in |
| **Test Steps** | 1. Perform various actions (create, update, delete)<br>2. Navigate to Audit Trail<br>3. Verify actions are logged |
| **Expected Result** | All actions logged with timestamp, user, details |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-SEC-004: AI Identity Verification
| Field | Value |
|-------|-------|
| **Objective** | Verify AI matches selfie to ID photo |
| **Preconditions** | User registering with valid ID |
| **Test Steps** | 1. Upload government ID<br>2. Take selfie matching ID<br>3. System performs verification |
| **Expected Result** | Match confidence score displayed, verification passes |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## 6. Analytics & Reports Module

### TC-RPT-001: Export to Excel
| Field | Value |
|-------|-------|
| **Objective** | Verify data can be exported to Excel |
| **Preconditions** | Admin logged in, data exists |
| **Test Steps** | 1. Navigate to Analytics<br>2. Select Booking Statistics<br>3. Click "Export to Excel" |
| **Expected Result** | Excel file downloaded with correct data |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-RPT-002: Export to PDF
| Field | Value |
|-------|-------|
| **Objective** | Verify reports can be exported to PDF |
| **Preconditions** | Treasurer logged in |
| **Test Steps** | 1. Navigate to Reports<br>2. Select report type<br>3. Click "Export PDF" |
| **Expected Result** | PDF file downloaded with correct formatting |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-RPT-003: Dashboard Analytics Display
| Field | Value |
|-------|-------|
| **Objective** | Verify dashboard shows correct statistics |
| **Preconditions** | Admin logged in, data exists |
| **Test Steps** | 1. Navigate to Dashboard<br>2. Verify booking counts<br>3. Verify revenue figures<br>4. Verify chart data |
| **Expected Result** | All statistics accurate and up-to-date |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## 7. UI/UX Module

### TC-UI-001: Responsive Design (Mobile)
| Field | Value |
|-------|-------|
| **Objective** | Verify system works on mobile devices |
| **Preconditions** | None |
| **Test Steps** | 1. Open system on mobile browser<br>2. Navigate through main pages<br>3. Test forms and buttons |
| **Expected Result** | All elements properly sized and functional |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-UI-002: Responsive Design (Tablet)
| Field | Value |
|-------|-------|
| **Objective** | Verify system works on tablet devices |
| **Preconditions** | None |
| **Test Steps** | 1. Open system on tablet browser<br>2. Navigate through main pages<br>3. Test forms and buttons |
| **Expected Result** | All elements properly sized and functional |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

### TC-UI-003: Browser Compatibility
| Field | Value |
|-------|-------|
| **Objective** | Verify system works on major browsers |
| **Preconditions** | None |
| **Test Steps** | 1. Test on Chrome<br>2. Test on Firefox<br>3. Test on Edge<br>4. Test on Safari |
| **Expected Result** | Consistent functionality across browsers |
| **Status** | ☐ Pass ☐ Fail |
| **Notes** | |

---

## Test Summary

| Module | Total Tests | Passed | Failed | Not Run |
|--------|-------------|--------|--------|---------|
| Authentication | 5 | | | |
| Booking | 5 | | | |
| Payment | 3 | | | |
| Facility Management | 3 | | | |
| Security | 4 | | | |
| Analytics & Reports | 3 | | | |
| UI/UX | 3 | | | |
| **TOTAL** | **26** | | | |

---

**Tested By:** ____________________  
**Date:** ____________________  
**Signature:** ____________________
