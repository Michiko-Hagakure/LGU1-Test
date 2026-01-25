# LGU1 Facilities Reservation System - Security Policy

**Document Version:** 1.0  
**Last Updated:** January 24, 2026  
**Classification:** Internal Use  

---

## 1. Purpose

This Security Policy establishes the security requirements and controls for the LGU1 Public Facilities Reservation System to protect citizen data, ensure system integrity, and comply with the Data Privacy Act of 2012 (RA 10173) and ISO 27001 standards.

---

## 2. Scope

This policy applies to:
- All system users (Citizens, Staff, Admins, Treasurers, SuperAdmins)
- All system components (Web Application, Databases, APIs)
- All data processed by the system (Personal data, Transaction data, Audit logs)

---

## 3. Authentication & Access Control

### 3.1 Authentication Mechanisms
| Control | Implementation |
|---------|----------------|
| Password Hashing | Bcrypt algorithm with cost factor 10 |
| OTP Verification | 6-digit code via email, 1-minute expiry |
| Session Management | Server-side sessions with timeout |
| AI Identity Verification | Azure Face API for selfie-to-ID matching |

### 3.2 Role-Based Access Control (RBAC)
| Role | Access Level |
|------|--------------|
| Citizen | Personal bookings, payments, profile |
| Staff | Booking verification, facility management |
| Admin | Full facility management, analytics, user management |
| Treasurer | Payment verification, financial reports |
| SuperAdmin | System configuration, role management |

### 3.3 Session Security
- Session timeout: 30 minutes of inactivity
- Secure session cookies (HttpOnly, SameSite)
- Session regeneration on login
- Single session per user (configurable)

---

## 4. Data Protection

### 4.1 Data Classification
| Classification | Examples | Protection |
|----------------|----------|------------|
| Public | Facility names, locations | None required |
| Internal | Booking statistics | Access control |
| Confidential | User emails, phone numbers | Encryption + Access control |
| Sensitive | Government IDs, selfies | Encryption + Audit trail + Limited access |

### 4.2 Encryption Standards
- **In Transit:** TLS 1.2+ (HTTPS enforced)
- **At Rest:** Database encryption for sensitive fields
- **Passwords:** Bcrypt hashing (one-way)

### 4.3 Data Retention
| Data Type | Retention Period |
|-----------|------------------|
| User accounts | Until deletion request |
| Booking records | 5 years |
| Payment records | 7 years |
| Audit logs | 3 years |
| Session data | 24 hours |

---

## 5. Application Security

### 5.1 Input Validation & Sanitization
- All user inputs validated server-side
- Laravel's built-in XSS protection via Blade templating
- SQL injection prevention via Eloquent ORM
- File upload validation (type, size, extension)

### 5.2 CSRF Protection
- CSRF tokens on all forms
- Token validation on state-changing requests
- SameSite cookie attribute enabled

### 5.3 Rate Limiting
| Endpoint | Limit |
|----------|-------|
| Login attempts | 5 per minute |
| OTP requests | 3 per 5 minutes |
| API calls | 60 per minute |
| Password reset | 3 per hour |

---

## 6. Audit & Monitoring

### 6.1 Audit Trail
All critical actions are logged:
- User authentication (login/logout)
- Data modifications (create/update/delete)
- Access to sensitive data
- Administrative actions
- Payment transactions

### 6.2 Log Contents
Each audit log entry contains:
- Timestamp (Philippine Time)
- User ID and role
- Action performed
- IP address
- Affected resource
- Previous and new values (for updates)

### 6.3 Log Protection
- Logs stored in separate database table
- Read-only access for auditors
- Tamper-evident logging
- Regular backup with main database

---

## 7. Incident Response

### 7.1 Incident Classification
| Severity | Description | Response Time |
|----------|-------------|---------------|
| Critical | Data breach, system compromise | Immediate |
| High | Unauthorized access attempt | 1 hour |
| Medium | Policy violation | 24 hours |
| Low | Minor security event | 72 hours |

### 7.2 Response Procedure
1. **Detection** - Identify and confirm the incident
2. **Containment** - Isolate affected systems
3. **Eradication** - Remove the threat
4. **Recovery** - Restore normal operations
5. **Lessons Learned** - Document and improve

### 7.3 Notification Requirements
- Data breaches affecting personal data: Notify NPC within 72 hours
- Affected users: Notify within 72 hours of discovery
- Document all incidents in incident log

---

## 8. Backup & Recovery

### 8.1 Backup Schedule
| Type | Frequency | Retention |
|------|-----------|-----------|
| Full database | Daily | 30 days |
| Incremental | Every 6 hours | 7 days |
| Configuration | On change | 90 days |

### 8.2 Backup Security
- Encrypted backup files (AES-256)
- OTP-protected download access
- Off-site storage (when configured)
- Regular restore testing

---

## 9. Compliance

### 9.1 Data Privacy Act (RA 10173)
- [ ] Privacy Policy published and accessible
- [ ] Data Processing Agreement available
- [ ] Consent collection on registration
- [ ] Data subject rights implemented (access, correction, deletion)
- [ ] Breach notification procedure documented

### 9.2 ISO 27001 Controls
- [ ] Access control (A.9)
- [ ] Cryptography (A.10)
- [ ] Operations security (A.12)
- [ ] Communications security (A.13)
- [ ] System development security (A.14)

---

## 10. Review & Updates

This policy shall be reviewed:
- Annually (minimum)
- After any security incident
- When significant system changes occur
- When regulatory requirements change

---

**Document Owner:** System Administrator  
**Approved By:** [Name], [Title]  
**Approval Date:** [Date]
