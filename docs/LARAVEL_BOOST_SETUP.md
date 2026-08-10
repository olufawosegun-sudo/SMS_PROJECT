# 🚀 Laravel Boost Setup Guide

Laravel Boost has been successfully installed! This guide will help you complete the setup.

---

## ✅ Installation Status

- ✅ **Laravel Boost Package** - Installed (v2.4.13)
- ✅ **Laravel MCP** - Installed (v0.9.1)
- ✅ **Laravel Roster** - Installed (v0.5.1)

---

## 🎯 What is Laravel Boost?

Laravel Boost is an **AI-assisted development tool** that provides:

1. **MCP Server** - 15+ tools for AI agents to inspect your Laravel app
2. **AI Guidelines** - Best practices for Laravel ecosystem
3. **Agent Skills** - On-demand knowledge for specific tasks
4. **Documentation API** - 17,000+ pieces of Laravel information

---

## 🛠️ Complete Setup

### Step 1: Run Interactive Installation

Run the following command and select options based on your AI coding tool:

```bash
php artisan boost:install
```

**Options to select:**
1. **Features**: Choose all (guidelines, skills, mcp)
2. **Integrations**: Select your AI tool (Cursor, Claude Code, Windsurf, etc.)

### Step 2: Available AI Tools

Laravel Boost supports:
- ✨ **Cursor** - AI code editor
- ✨ **Claude Code** - Claude AI coding assistant
- ✨ **Windsurf** - AI coding tool
- ✨ **Codex** - OpenAI Codex
- ✨ **Gemini CLI** - Google Gemini
- ✨ **Junie** - AI assistant

### Step 3: MCP Server Configuration

After running `boost:install`, you'll have:

**File**: `.mcp.json`
```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "php",
            "args": ["artisan", "boost:mcp"]
        }
    }
}
```

---

## 📚 Available MCP Tools (15+)

Once configured, AI agents can use these tools:

| Tool | Purpose |
|------|---------|
| **Application Info** | PHP & Laravel versions, packages, models |
| **Browser Logs** | Read errors from browser |
| **Database Connections** | Inspect DB connections |
| **Database Query** | Execute queries |
| **Database Schema** | Read DB structure |
| **Get Absolute URL** | Convert paths to URLs |
| **Last Error** | Read recent errors |
| **Read Log Entries** | Access logs |
| **Search Docs** | Query Laravel documentation API |

---

## 🎨 AI Guidelines Included

Boost includes guidelines for:

- ✅ Laravel Framework (10.x, 11.x, 12.x)
- ✅ Livewire (2.x, 3.x, 4.x)
- ✅ Inertia (React, Vue, Svelte)
- ✅ Pest Testing
- ✅ Tailwind CSS
- ✅ Flux UI
- ✅ Folio
- ✅ And more...

---

## 🔧 Agent Skills Available

On-demand skills for specific tasks:

- 📦 **livewire-development** - Livewire components
- 📦 **inertia-react-development** - Inertia React
- 📦 **pest-testing** - Pest testing
- 📦 **tailwindcss-development** - Tailwind CSS
- 📦 **folio-routing** - Folio routing
- 📦 **volt-development** - Livewire Volt

---

## 🎯 Manual Setup (If Needed)

### For Cursor

1. Open Command Palette (`Cmd+Shift+P` or `Ctrl+Shift+P`)
2. Search "/open MCP Settings"
3. Enable `laravel-boost`

### For Claude Code

```bash
claude mcp add -s local -t stdio laravel-boost php artisan boost:mcp
```

### For Windsurf

1. Open Command Palette
2. Search "MCP Settings"
3. Check `laravel-boost`
4. Click "Apply"

---

## 📝 Custom Guidelines

You can add your own AI guidelines:

**Create**: `.ai/guidelines/custom.blade.php` or `.ai/guidelines/custom.md`

Example:
```markdown
## My Custom Guidelines

### Project Structure
- Use repositories for data access
- Use services for business logic
- Use policies for authorization

### Naming Conventions
- Controllers: `StudentController`
- Services: `StudentService`
- Repositories: `StudentRepository`
```

---

## 🛠️ Custom Skills

Create custom skills for your domain:

**Create**: `.ai/skills/creating-invoices/SKILL.md`

```markdown
---
name: creating-invoices
description: Create and manage invoices in the SMS system
---

# Creating Invoices

## When to use this skill
Use when working with invoice creation and payment processing.

## Steps
1. Create invoice via InvoiceService
2. Generate PDF with DomPDF
3. Send email notification
```

---

## 🔄 Keeping Boost Updated

Update Boost resources regularly:

```bash
# Manual update
php artisan boost:update

# Auto-update after composer update
# Add to composer.json:
{
  "scripts": {
    "post-update-cmd": [
      "@php artisan boost:update --ansi"
    ]
  }
}
```

---

## 📊 Boost Features for Your SMS Project

### What Boost Will Help With:

1. **Code Generation**
   - Generate controllers with proper patterns
   - Create repositories following best practices
   - Build services with business logic

2. **Database Queries**
   - AI can query your database structure
   - See relationships between models
   - Generate migrations

3. **Documentation Search**
   - AI searches Laravel docs automatically
   - Gets package-specific information
   - Finds best practices

4. **Error Resolution**
   - AI reads your logs
   - Understands errors
   - Suggests fixes

---

## 🎓 Using Boost with AI Coding Tools

### Cursor Example

```
# Ask AI to:
"Create a StudentRepository following the project's repository pattern"

# Boost provides:
- Your existing repository structure
- Laravel best practices
- Your coding standards
```

### Claude Code Example

```
# Ask Claude:
"Add a new payment gateway integration"

# Boost helps with:
- Your payment service structure
- Database schema
- Laravel payment patterns
```

---

## 🚀 Quick Start Commands

```bash
# Install Boost (interactive)
php artisan boost:install

# Update Boost resources
php artisan boost:update

# Start MCP server (if needed manually)
php artisan boost:mcp
```

---

## 📚 Documentation API

Boost provides access to 17,000+ pieces of Laravel info:

- Laravel Framework docs (10.x, 11.x, 12.x)
- Filament docs
- Livewire docs
- Inertia docs
- Pest docs
- And more...

AI agents automatically search these when needed!

---

## 💡 Best Practices

### 1. Keep Boost Updated
```bash
php artisan boost:update
```

### 2. Add Custom Guidelines
Create `.ai/guidelines/` for your team standards

### 3. Use Skills
Activate skills when working on specific features

### 4. Leverage MCP Tools
Let AI query your database and read logs

---

## 🔍 Troubleshooting

### Boost Not Working?

1. **Verify installation**:
   ```bash
   composer show laravel/boost
   ```

2. **Re-run install**:
   ```bash
   php artisan boost:install
   ```

3. **Check MCP config**:
   Look for `.mcp.json` file in project root

4. **Restart your IDE/AI tool**

---

## 📞 Support

- **Laravel Boost Docs**: https://laravel.com/docs/12.x/boost
- **MCP Protocol**: https://modelcontextprotocol.io
- **GitHub Issues**: Report problems on Laravel's GitHub

---

## 🎉 Benefits for Your SMS Project

With Laravel Boost configured:

✅ AI understands your repository pattern
✅ AI follows your service layer structure
✅ AI uses your policies correctly
✅ AI generates code matching your standards
✅ AI can query your database
✅ AI reads your logs and errors
✅ AI searches Laravel docs automatically

---

## 📈 Next Steps

1. ✅ **Complete interactive setup**: `php artisan boost:install`
2. ✅ **Configure your AI tool** (Cursor, Claude Code, etc.)
3. ✅ **Add custom guidelines** for your team
4. ✅ **Create custom skills** for domain logic
5. ✅ **Start coding** with AI assistance!

---

<div align="center">

**[⬆ Back to README](README.md)** | **[📚 Documentation](docs/INDEX.md)**

---

*Last Updated: 2026-07-29*
*Laravel Boost Version: 2.4.13*

</div>
