# Password Hacking Demo

An interactive demonstration showing how quickly simple passwords can be cracked using dictionary attacks. Perfect for security awareness training and presentations.

<img width="1920" height="929" alt="Demo" src="https://github.com/user-attachments/assets/24941820-0f17-4c91-86bf-7cefb27969b9" />

## 🎯 What It Does

1. **User Portal** (`index.html`) — Participants create an "account" with a simple password
2. **Hacker Terminal** (`terminal.html`) — Demonstrates cracking the password in real-time
3. **Backend API** (`api/store.php`) — Stores hashed passwords so two devices can communicate

## Demo Video

https://github.com/user-attachments/assets/03b3921f-8f05-49f1-926b-8a5fdb7df5b4

## Front-End Account Creation Screen

<img width="1920" height="929" alt="Create-Password" src="https://github.com/user-attachments/assets/f8b62e58-0e51-4c44-a230-87e083c2a5c3" />

## 📁 Folder Structure

```
├── index.html          # User-facing account creation page
├── terminal.html       # Hacker terminal interface
├── api/
│   └── store.php       # PHP backend for storing hashes
├── docs/
│   ├── Operators_Guide.docx         # Setup & troubleshooting guide
│   └── Password_Creation_Guide.docx # Handout for participants
├── .gitignore
└── README.md
```

## 🚀 Quick Setup

1. **Upload files** to your web server maintaining the folder structure
2. **Set permissions** on the `api/` folder to `755` (needs write access)
3. **Update the logo** in `index.html` (line ~354) if desired
4. **Test the API** — see Debugging section below

## ⚙️ Configuration

Both HTML files have configurable options near the top of their `<script>` sections:

| Variable | File | Default | Description |
|----------|------|---------|-------------|
| `MAX_TABLES` | Both | 15 | Number of tables/groups |
| `DEMO_TABLE` | terminal.html | 15 | Table that always runs full wordlist (strong password demo) |

## 🔧 Debugging

Open your browser console (F12) and run these commands:

**Check API connectivity:**
```javascript
fetch('api/store.php').then(r => r.json()).then(console.log)
// Expected: {success: true, tables: [...]}
```

**Test storing a hash:**
```javascript
fetch('api/store.php', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({table: 99, hash: 'test123'})
}).then(r => r.json()).then(console.log)
// Expected: {success: true, message: 'Hash stored for Table 99'}
```

**Watch for this console message when creating accounts:**
- ✅ `Password hash stored on server` — Working correctly
- ❌ `Password hash stored locally (demo mode)` — API not working, fix permissions

## 📋 Demo Flow

1. Hand out the **Password Creation Guide** to participants
2. Each table creates a password on their phones via the **User Portal**
3. On the big screen, open the **Hacker Terminal**
4. Select a table, intercept their hash, and launch the attack
5. Watch as simple passwords get cracked in seconds!
6. Use the **DEMO_TABLE** (Table 15) to show a "strong password" that can't be cracked

## 📄 Documentation

- **Operators_Guide.docx** — Full setup, permissions, customization, and troubleshooting
- **Password_Creation_Guide.docx** — Printable handout for participants with the word/number lists

## 🔐 How It Works

- Passwords are hashed client-side using MD5 (intentionally weak for demo purposes)
- The terminal runs a dictionary attack with ~200,000+ password combinations
- Combinations include: common words + numbers, multiple capitalizations, number prefixes/suffixes

## ⚠️ Disclaimer

This tool is for **educational purposes only**. It demonstrates why simple passwords are insecure. Never use these techniques on systems you don't own or have permission to test.

## License

MIT License — Feel free to use and modify for your own security training.
