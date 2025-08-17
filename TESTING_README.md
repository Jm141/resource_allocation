# 🧪 Resource Allocation System - Testing Guide

This guide provides comprehensive testing instructions for the Resource Allocation System with integrated Emergency Hotline services.

## 🚀 Quick Start Testing

### 1. System Integrity Test
Run the main system test to check all components:
```bash
# Navigate to your project directory
cd /path/to/Resource_Allocation

# Run the system integrity test
php test_system.php
```

### 2. Hotline Unit Tests
Run specific hotline system tests:
```bash
# Navigate to tests directory
cd tests

# Run hotline unit tests
php hotline_unit_test.php
```

## 📋 Pre-Testing Checklist

Before running tests, ensure:

- [ ] XAMPP/WAMP is running
- [ ] MySQL service is active
- [ ] Database `resource_allocation` exists
- [ ] All files are in the correct directory structure
- [ ] Database credentials are correct in `config/database.php`

## 🔧 Database Setup

### 1. Create Database Tables
```sql
-- Run the hotline schema
SOURCE database/hotline_schema.sql;

-- Verify table creation
SHOW TABLES LIKE 'hotline_requests';
DESCRIBE hotline_requests;
```

### 2. Verify Database Connection
```bash
php -r "
require_once 'config/database.php';
\$db = new Database();
\$conn = \$db->getConnection();
if (\$conn) echo 'Database connected successfully\n';
else echo 'Database connection failed\n';
"
```

## 🧪 Test Categories

### 1. System Integrity Tests (`test_system.php`)

**What it tests:**
- ✅ Database connectivity
- ✅ File structure completeness
- ✅ Controller class loading
- ✅ Webhook endpoint existence
- ✅ API endpoint functionality
- ✅ Configuration file validity
- ✅ Page routing system
- ✅ Hotline integration
- ✅ Database schema
- ✅ Documentation completeness

**Expected Output:**
- All components should show ✅ (success)
- Any ❌ (failures) need immediate attention
- System status should show "READY FOR TESTING"

### 2. Hotline Unit Tests (`tests/hotline_unit_test.php`)

**What it tests:**
- ✅ HotlineModel class functionality
- ✅ HotlineController class methods
- ✅ Database schema validation
- ✅ Webhook endpoint syntax
- ✅ API endpoint syntax
- ✅ Configuration file validity
- ✅ Integration between components
- ✅ Security measures

**Expected Output:**
- All tests should pass (80%+ success rate)
- System should show "READY FOR PRODUCTION"

## 🌐 Manual Page Testing

### 1. Dashboard Test
```bash
# Test main dashboard
http://localhost/Resource_Allocation/
```

**Expected Result:**
- Dashboard loads without errors
- Statistics cards display correctly
- Navigation menu is functional
- Hotline section is visible

### 2. Navigation Test
Test all navigation links:
- [ ] Dashboard
- [ ] Incidents
- [ ] Deployments
- [ ] Live Map
- [ ] Report Incident
- [ ] Emergency Hotline

### 3. Hotline Dashboard Test
```bash
# Test hotline dashboard
http://localhost/Resource_Allocation/index.php?action=hotline
```

**Expected Result:**
- Dashboard loads without errors
- Statistics display correctly
- Request management interface is functional

## 📱 Hotline Integration Testing

### 1. Webhook Testing
Test webhook endpoints manually:

**SMS Webhook:**
```bash
curl -X POST http://localhost/Resource_Allocation/webhooks/sms_webhook.php \
  -d "From=+1234567890" \
  -d "Body=Fire emergency at 123 Main St" \
  -d "MessageSid=test123"
```

**Voice Webhook:**
```bash
curl -X POST http://localhost/Resource_Allocation/webhooks/voice_webhook.php \
  -d "From=+1234567890" \
  -d "CallSid=test456"
```

### 2. API Endpoint Testing
Test API endpoints:

**Get Pending Requests:**
```bash
curl http://localhost/Resource_Allocation/api/hotline_requests.php
```

**Get Statistics:**
```bash
curl http://localhost/Resource_Allocation/api/hotline_statistics.php
```

## 🔒 Security Testing

### 1. Input Validation
- Test webhook endpoints with invalid data
- Verify error handling for malformed requests
- Check SQL injection protection

### 2. Access Control
- Verify webhook endpoints only accept POST requests
- Test with different HTTP methods
- Check for proper error responses

## 📊 Performance Testing

### 1. Database Performance
```sql
-- Test hotline requests table performance
EXPLAIN SELECT * FROM hotline_requests WHERE status = 'pending';
```

### 2. Response Time
- Monitor webhook response times
- Test with multiple concurrent requests
- Verify system stability under load

## 🐛 Troubleshooting Common Issues

### 1. Database Connection Errors
**Problem:** "Connection failed" error
**Solution:**
- Check MySQL service is running
- Verify database credentials in `config/database.php`
- Ensure database `resource_allocation` exists

### 2. Missing Content File Errors
**Problem:** "Path cannot be empty" error
**Solution:**
- Verify all content files exist
- Check file permissions
- Ensure proper file paths in view files

### 3. Hotline Table Missing
**Problem:** "Table doesn't exist" error
**Solution:**
```sql
-- Run the schema file
SOURCE database/hotline_schema.sql;
```

### 4. Twilio Configuration Issues
**Problem:** Webhook not receiving requests
**Solution:**
- Update `config/twilio_config.php` with real credentials
- Verify webhook URLs in Twilio console
- Check HTTPS requirement for production

## 📈 Test Results Interpretation

### Success Criteria
- **System Integrity Test:** 100% components working
- **Unit Tests:** 80%+ test pass rate
- **Manual Testing:** All pages load without errors
- **Integration Testing:** Hotline system fully functional

### Failure Indicators
- ❌ Database connection failures
- ❌ Missing files or directories
- ❌ PHP syntax errors
- ❌ Missing database tables
- ❌ Webhook endpoint failures

## 🚀 Production Readiness Checklist

Before deploying to production:

- [ ] All tests pass (80%+ success rate)
- [ ] Database schema is complete
- [ ] Twilio credentials are configured
- [ ] Webhook URLs are publicly accessible
- [ ] HTTPS is enabled
- [ ] Error logging is configured
- [ ] Security measures are implemented
- [ ] Documentation is complete

## 📞 Support & Troubleshooting

### Getting Help
1. Check error logs in XAMPP/WAMP
2. Verify file permissions
3. Test database connectivity
4. Check PHP syntax with `php -l filename.php`
5. Review the setup guide in `HOTLINE_SETUP.md`

### Common Commands
```bash
# Check PHP syntax
php -l filename.php

# Test database connection
php -r "require 'config/database.php'; new Database();"

# Check file existence
ls -la views/dashboard/

# View error logs
tail -f /path/to/error.log
```

## 🎯 Next Steps After Testing

1. **Fix any failed tests** before proceeding
2. **Configure Twilio** with real credentials
3. **Test with real SMS/voice calls**
4. **Deploy to production server**
5. **Monitor system performance**
6. **Train operators on hotline dashboard**

---

**Remember:** Testing is crucial for system reliability. Run tests regularly and fix issues promptly to ensure a robust emergency response system. 