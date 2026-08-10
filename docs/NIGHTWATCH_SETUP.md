# 🌙 Laravel Nightwatch Setup Guide

**Status:** ✅ Installed (Configuration Required)

---

## ✅ What Was Done

### 1. **Package Installed**
```bash
composer require laravel/nightwatch
# Version: v1.28.5 ✅
```

### 2. **Configuration Published**
```bash
php artisan vendor:publish --tag=nightwatch-config
# Created: config/nightwatch.php ✅
```

### 3. **Environment Variables Added**
Added to `.env`:
```env
NIGHTWATCH_ENABLED=true
NIGHTWATCH_TOKEN=
NIGHTWATCH_SERVER=localhost
NIGHTWATCH_CAPTURE_EXCEPTION_SOURCE_CODE=true
NIGHTWATCH_CAPTURE_REQUEST_PAYLOAD=false
NIGHTWATCH_REQUEST_SAMPLE_RATE=1.0
NIGHTWATCH_LOG_LEVEL=debug
```

---

## 🚨 IMPORTANT: Next Steps Required

### **Laravel Nightwatch is a PAID, HOSTED Service**

To use Nightwatch, you need to:

1. **Sign up at:** [https://nightwatch.laravel.com](https://nightwatch.laravel.com)
2. **Get your API token** from the dashboard
3. **Add the token** to your `.env` file
4. **Run the agent** to start monitoring

**Without an account and API token, Nightwatch will not work!**

---

## 📋 Complete Setup Steps

### **Step 1: Create Nightwatch Account**

**Go to:** [https://nightwatch.laravel.com](https://nightwatch.laravel.com)

1. Click **"Sign Up"** or **"Get Started"**
2. Create your account (email, password)
3. Choose a subscription plan
4. Verify your email

**Plans (estimated):**
- Starter: ~$50/month
- Professional: ~$100/month
- Enterprise: ~$200+/month

*(Check website for actual pricing)*

---

### **Step 2: Get Your API Token**

Once logged in:

1. Go to **Settings** or **Projects**
2. Create a new project: "West African SMS System"
3. Copy your **API Token** (looks like: `nw_xxxxxxxxxxxxxxxxxxxxxx`)

---

### **Step 3: Add Token to .env**

Open `.env` and update:

```env
NIGHTWATCH_TOKEN=nw_your_actual_token_here
```

**⚠️ Important:** Keep this token SECRET! Don't commit it to git!

---

### **Step 4: Start the Nightwatch Agent**

The agent collects data from your app and sends it to Nightwatch:

#### **Development (Manual):**
```bash
# Terminal 1: Your app
php artisan serve

# Terminal 2: Nightwatch agent
php artisan nightwatch:agent
```

#### **Production (Supervisor):**

Create supervisor config: `/etc/supervisor/conf.d/nightwatch.conf`

```ini
[program:nightwatch-agent]
process_name=%(program_name)s
command=php /path/to/SMS_PROJECT/artisan nightwatch:agent
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/SMS_PROJECT/storage/logs/nightwatch.log
stopwaitsecs=3600
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start nightwatch-agent
```

---

### **Step 5: Verify Installation**

```bash
# Check agent status
php artisan nightwatch:status

# Should see:
# ✓ Nightwatch agent is running
# ✓ Connected to nightwatch.laravel.com
# ✓ Monitoring enabled
```

---

### **Step 6: View Your Dashboard**

Go to: [https://nightwatch.laravel.com/dashboard](https://nightwatch.laravel.com/dashboard)

You should see:
- ✅ Requests coming in
- ✅ Database queries logged
- ✅ Events tracked
- ✅ Real-time monitoring

---

## 🎯 What Nightwatch Will Monitor

Once configured, Nightwatch automatically tracks:

### **1. HTTP Requests**
- URL, method, status code
- Response time
- Memory usage
- User information

### **2. Database Queries**
- SQL queries executed
- Query time
- Slow query detection (> 100ms)
- N+1 query detection

### **3. Queue Jobs**
- Job name and payload
- Execution time
- Success/failure status
- Retry attempts

### **4. Exceptions**
- Exception type and message
- Stack trace
- Affected users
- Frequency

### **5. Cache Operations**
- Cache hits/misses
- Keys accessed
- Invalidations

### **6. Email Sending**
- Recipients
- Subject
- Delivery status
- Send time

### **7. Scheduled Tasks**
- Cron job execution
- Duration
- Success/failure
- Output

### **8. Artisan Commands**
- Command name
- Arguments
- Execution time
- Output

### **9. External API Calls**
- URL called
- Response time
- Status codes
- Failures

### **10. Notifications**
- Type (email, SMS, Slack, etc.)
- Recipients
- Delivery status

---

## ⚙️ Configuration Options

### **Enable/Disable Monitoring**

```env
# Disable in development/testing
NIGHTWATCH_ENABLED=false

# Enable in production
NIGHTWATCH_ENABLED=true
```

### **Sampling Rate (Reduce Data Volume)**

```env
# Monitor 100% of requests (default)
NIGHTWATCH_REQUEST_SAMPLE_RATE=1.0

# Monitor 50% of requests (save costs)
NIGHTWATCH_REQUEST_SAMPLE_RATE=0.5

# Monitor 10% of requests (high traffic apps)
NIGHTWATCH_REQUEST_SAMPLE_RATE=0.1
```

### **Capture Request Payloads**

```env
# Don't capture (more secure, less storage)
NIGHTWATCH_CAPTURE_REQUEST_PAYLOAD=false

# Capture (for debugging)
NIGHTWATCH_CAPTURE_REQUEST_PAYLOAD=true
```

### **Redact Sensitive Data**

```env
# Fields to hide in payloads
NIGHTWATCH_REDACT_PAYLOAD_FIELDS=_token,password,password_confirmation,card_number,cvv

# Headers to hide
NIGHTWATCH_REDACT_HEADERS=Authorization,Cookie,X-API-Key
```

### **Ignore Specific Events**

```env
# Ignore cache events
NIGHTWATCH_IGNORE_CACHE_EVENTS=true

# Ignore email events
NIGHTWATCH_IGNORE_MAIL=true

# Ignore database queries
NIGHTWATCH_IGNORE_QUERIES=true
```

---

## 💰 Pricing & Plans

**Laravel Nightwatch is a paid service** (subscription-based):

### **Estimated Pricing:**
- **Starter:** ~$50/month
  - Up to 1M events/month
  - 30 days data retention
  - Basic features

- **Professional:** ~$100-150/month
  - Up to 10M events/month
  - 90 days data retention
  - Advanced features
  - Team collaboration

- **Enterprise:** Custom pricing
  - Unlimited events
  - Custom retention
  - Priority support
  - SLA guarantees

**Check:** [https://nightwatch.laravel.com/pricing](https://nightwatch.laravel.com/pricing) for actual pricing

---

## 🆚 Nightwatch vs Free Alternatives

### **Should I Pay for Nightwatch?**

| Your Situation | Recommendation |
|----------------|----------------|
| **Development/Testing** | ❌ Use **Telescope** (free) |
| **Small school (< 100 students)** | ❌ Use **Pulse** (free) |
| **Medium school (100-500 students)** | ⚠️ Consider Nightwatch if budget allows |
| **Large school (500+ students)** | ✅ **Nightwatch recommended** |
| **Multiple schools (branches)** | ✅ **Nightwatch highly recommended** |
| **Generating revenue** | ✅ **Worth the investment** |
| **Tight budget** | ❌ Use **Pulse + UptimeRobot** (free) |

---

## 🆓 Free Alternative: Laravel Pulse

If you're not ready to pay for Nightwatch yet:

```bash
# Install Pulse (free, self-hosted)
composer require laravel/pulse
php artisan pulse:install
php artisan migrate

# Access at: http://localhost:8000/pulse
```

**Pulse gives you 80% of Nightwatch features for free!**

Differences:
- ✅ Pulse: Free, self-hosted, good for most use cases
- ✅ Nightwatch: Paid, hosted, advanced features, team collaboration

---

## 🚀 Quick Start Commands

### **Run Agent (Development):**
```bash
php artisan nightwatch:agent
```

### **Check Status:**
```bash
php artisan nightwatch:status
```

### **Deploy Notification:**
```bash
php artisan nightwatch:deploy
```

### **Disable Monitoring:**
```env
NIGHTWATCH_ENABLED=false
```

---

## 📊 Expected Performance Impact

**Nightwatch is designed to be lightweight:**

- ✅ **< 3ms overhead** per request (typical)
- ✅ **Asynchronous data collection** (doesn't block requests)
- ✅ **Buffered events** (batched sending)
- ✅ **Minimal memory usage** (< 10MB typically)

**Your users won't notice any slowdown!**

---

## 🔐 Security & Privacy

### **What Data Is Sent?**
- Application events (requests, queries, jobs)
- Exception details
- Performance metrics
- User IDs (can be redacted)

### **What Is NOT Sent?**
- ❌ Passwords (automatically redacted)
- ❌ API tokens (redacted by default)
- ❌ Credit card numbers (redacted)
- ❌ Personal data (if configured to redact)

### **Data Location:**
- Hosted on secure AWS infrastructure
- Encrypted in transit (HTTPS)
- Encrypted at rest
- GDPR compliant

---

## 🛠️ Troubleshooting

### **Agent Not Connecting:**

**Check 1:** Is token correct?
```bash
# In .env
NIGHTWATCH_TOKEN=nw_your_token_here
```

**Check 2:** Is agent running?
```bash
php artisan nightwatch:status
```

**Check 3:** Check logs
```bash
tail -f storage/logs/laravel.log
```

### **No Data in Dashboard:**

**Solution 1:** Wait a few minutes (data is batched)

**Solution 2:** Check sampling rate
```env
NIGHTWATCH_REQUEST_SAMPLE_RATE=1.0  # Make sure it's 1.0 for testing
```

**Solution 3:** Generate some activity
```bash
# Make requests to your app
curl http://localhost:8000
curl http://localhost:8000/students
```

### **High Costs:**

**Reduce sampling rate:**
```env
# Monitor only 10% of requests
NIGHTWATCH_REQUEST_SAMPLE_RATE=0.1
```

**Ignore noisy events:**
```env
NIGHTWATCH_IGNORE_CACHE_EVENTS=true
NIGHTWATCH_IGNORE_QUERIES=true  # If you have many queries
```

---

## 📚 Documentation

**Official Docs:** [https://nightwatch.laravel.com/docs](https://nightwatch.laravel.com/docs)

**Key Resources:**
- Getting Started Guide
- Configuration Reference
- Integration with Forge/Vapor
- API Reference
- Pricing Information

---

## ✅ Setup Checklist

- [x] Package installed (`laravel/nightwatch` v1.28.5)
- [x] Configuration published (`config/nightwatch.php`)
- [x] Environment variables added (`.env`)
- [ ] **Create Nightwatch account** at [nightwatch.laravel.com](https://nightwatch.laravel.com)
- [ ] **Get API token** from dashboard
- [ ] **Add token to .env:** `NIGHTWATCH_TOKEN=nw_xxx`
- [ ] **Start agent:** `php artisan nightwatch:agent`
- [ ] **Verify status:** `php artisan nightwatch:status`
- [ ] **Check dashboard** at [nightwatch.laravel.com/dashboard](https://nightwatch.laravel.com/dashboard)

---

## 🎯 Next Steps

### **Right Now (Required):**

1. **Go to:** [https://nightwatch.laravel.com](https://nightwatch.laravel.com)
2. **Sign up** for an account
3. **Get your API token**
4. **Add to .env:** `NIGHTWATCH_TOKEN=nw_your_token`
5. **Run agent:** `php artisan nightwatch:agent`

### **Alternative (Free Option):**

If you're not ready to pay yet:

```bash
# Install Laravel Pulse instead (free)
composer require laravel/pulse
php artisan pulse:install
php artisan migrate
```

---

## 💡 Summary

**Laravel Nightwatch is installed but requires:**
- ✅ Account creation at [nightwatch.laravel.com](https://nightwatch.laravel.com)
- ✅ Paid subscription (~$50-200/month)
- ✅ API token configuration
- ✅ Running the agent

**Free Alternative:**
- Use **Laravel Pulse** for self-hosted monitoring (80% of features, $0 cost)

---

<div align="center">

**[🌙 Nightwatch Website](https://nightwatch.laravel.com)** | **[📚 Documentation](https://nightwatch.laravel.com/docs)** | **[💰 Pricing](https://nightwatch.laravel.com/pricing)**

---

*SMS Project - Nightwatch Setup Guide*  
*Last Updated: 2026-08-01*

</div>
