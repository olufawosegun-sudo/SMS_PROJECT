# Email Deliverability Guide - School Management System

## ✅ What Has Been Fixed

### 1. Email Configuration
- ✅ Changed `QUEUE_CONNECTION=sync` for immediate email sending
- ✅ Added encryption settings to SMTP configuration
- ✅ Configured proper Reply-To headers
- ✅ Added email priority and importance headers
- ✅ Improved email templates to be less "spammy"

### 2. Mail Classes Updated
All welcome mail classes now include:
- Proper `from` address with school name
- `replyTo` address for better deliverability
- Email headers (X-Mailer, X-Priority, Importance)
- Professional subject lines

### 3. Email Templates Updated
- Removed emojis (can trigger spam filters)
- Professional, clear language
- Proper formatting without excessive styling
- Clear call-to-action buttons

---

## 📧 Why Emails Go to Spam (Gmail Specific)

### Common Reasons:
1. **New sender / Low sender reputation** - Gmail doesn't recognize your email yet
2. **No SPF/DKIM records** - Email authentication not configured
3. **Gmail SMTP usage patterns** - Sending from a personal Gmail account
4. **Content triggers** - Certain words/patterns trigger spam filters
5. **Low engagement** - Recipients don't interact with your emails

---

## 🚀 How to Improve Email Deliverability

### Option 1: Use Gmail with Better Configuration (Current Setup)

**Advantages:**
- ✅ Quick to set up
- ✅ Free
- ✅ Works immediately

**Disadvantages:**
- ❌ May land in spam initially
- ❌ Limited sending volume (500/day)
- ❌ Less professional

**Steps to Improve:**

1. **Enable 2-Step Verification** on your Gmail account
   - Go to: https://myaccount.google.com/security
   - Enable 2-Step Verification

2. **Use App Password** (Already done ✅)
   - Your current password: `aqbejyahtjegbnyx`

3. **Warm up the sender reputation:**
   - Send emails to yourself first
   - Mark them as "Not Spam"
   - Reply to them
   - Move them to inbox
   - Star them
   - Do this for 5-10 emails

4. **Ask recipients to:**
   - Mark as "Not Spam" when they receive it
   - Add `segyrictech@gmail.com` to their contacts
   - Reply to the email (increases engagement)

### Option 2: Use a Professional Email Service (Recommended for Production)

**Best Options:**

#### A. **Mailgun** (Recommended)
- Free tier: 5,000 emails/month for 3 months
- Professional deliverability
- SPF/DKIM configured automatically

**Setup:**
1. Sign up: https://www.mailgun.com
2. Add your domain or use Mailgun sandbox
3. Update `.env`:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-secret-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="West African Excellence Academy"
```

#### B. **SendGrid**
- Free tier: 100 emails/day
- Excellent deliverability

**Setup:**
1. Sign up: https://sendgrid.com
2. Create API key
3. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### C. **Amazon SES**
- Very cheap: $0.10 per 1000 emails
- Highly reliable

**Setup:**
1. Sign up: https://aws.amazon.com/ses/
2. Verify your domain
3. Update `.env`:
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
```

### Option 3: Use Your Own Domain Email

If your school has a domain (e.g., `westafricanacademy.com`):

1. **Get email hosting** (Google Workspace, Microsoft 365, or cPanel)
2. **Configure SPF record** in your domain DNS:
```
v=spf1 include:_spf.google.com ~all
```
3. **Configure DKIM** in your domain DNS
4. **Update `.env`** with your domain email:
```env
MAIL_FROM_ADDRESS=noreply@westafricanacademy.com
MAIL_FROM_NAME="West African Excellence Academy"
```

---

## 🔧 Current Configuration

Your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=segyrictech@gmail.com
MAIL_PASSWORD="aqbejyahtjegbnyx"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="segyrictech@gmail.com"
MAIL_FROM_NAME="West African Excellence Academy"
QUEUE_CONNECTION=sync
```

---

## 📝 Testing Email Deliverability

### Test 1: Send to Yourself
```bash
php artisan tinker
Mail::raw('Test email', function($msg) { $msg->to('segyrictech@gmail.com')->subject('Test'); });
```

### Test 2: Check Spam Score
- Use: https://www.mail-tester.com
- Send an email to the address they provide
- Get a spam score out of 10

### Test 3: Monitor Logs
```bash
php artisan tail
```

---

## ⚡ Quick Fixes for Immediate Improvement

### 1. Whitelist Your Email
**For Gmail users:**
1. Open Gmail
2. Click gear icon → See all settings
3. Filters and Blocked Addresses → Create new filter
4. From: `segyrictech@gmail.com`
5. Check "Never send it to Spam"
6. Create filter

### 2. Add to Contacts
**For all users:**
1. Add `segyrictech@gmail.com` to contacts
2. This tells Gmail this is a trusted sender

### 3. Manual Inbox Move
**When you receive the email:**
1. Go to Spam folder
2. Select the email
3. Click "Not Spam"
4. Do this 3-4 times to train Gmail

---

## 📊 Monitor Email Health

### Check Delivery Logs
- Laravel logs email attempts in `storage/logs/laravel.log`
- Check for errors or delivery failures

### Gmail Postmaster Tools
- Sign up: https://postmaster.google.com
- Monitor your domain's email reputation
- See spam rates and delivery errors

---

## 🎯 Best Practices Going Forward

1. **Don't send too many emails at once** - Pace your sending
2. **Monitor bounce rates** - Remove invalid emails
3. **Provide unsubscribe option** - For announcement emails
4. **Keep content professional** - Avoid spam trigger words
5. **Test regularly** - Use mail-tester.com monthly
6. **Engage recipients** - Ask them to reply or interact

---

## 🔍 Troubleshooting

### Email not sending at all?
```bash
php artisan config:clear
php artisan cache:clear
# Check logs
tail -f storage/logs/laravel.log
```

### Email going to spam?
- Follow Option 1 warm-up steps above
- Consider switching to Option 2 (Mailgun/SendGrid)

### Gmail blocking?
- You might have hit the daily limit (500 emails)
- Wait 24 hours or switch to a dedicated service

---

## 📞 Support

For issues:
1. Check `storage/logs/laravel.log`
2. Test with `mail-tester.com`
3. Verify `.env` configuration is correct
4. Clear all caches

Last Updated: July 23, 2026
