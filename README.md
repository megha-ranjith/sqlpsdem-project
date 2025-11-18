<<<<<<< HEAD
# SQLPsdem: Proxy-Based Detection and Prevention of Second-Order SQL Injections

## 📋 Project Overview

This project implements a comprehensive SQL injection detection and prevention system based on proxy-based monitoring, static analysis, and dynamic execution testing.
---

## 🎯 Features

- ✅ **Static Analysis**: Locates SQL statements in PHP code
- ✅ **Attack Payload Generation**: Creates 10+ injection payloads across 6 attack types
- ✅ **First-Order Detection**: Detects direct SQL injections
- ✅ **Second-Order Detection**: Detects stored/delayed injections
- ✅ **Proxy Monitoring**: Captures and analyzes SQL queries
- ✅ **Prevention Mechanisms**: Parameterized queries and input validation
- ✅ **Comprehensive Reporting**: Detailed logs and HTML reports
---

## 🛠️ Technology Stack

- **Backend Detection**: Python 3.11
- **Vulnerable Web App**: PHP 8.2, MySQL/MariaDB
- **Libraries**: BeautifulSoup4, requests, lxml, pandas
---

## 📁 Project Structure
```
sqlpsdem-project/
├── vulnerable_app/          # Intentionally vulnerable PHP application
│   ├── config.php
│   ├── index.php
│   ├── login.php
│   ├── dashboard.php
│   ├── search.php
│   ├── comments.php
│   └── database_setup.sql
├── src/                     # Python detection modules
│   ├── utils/
│   │   ├── __init__.py
│   │   ├── config.py
│   │   └── logger.py
│   ├── static_analysis/
│   │   ├── __init__.py
│   │   └── sql_locator.py
│   └── dynamic_execution/
│       ├── __init__.py
│       ├── injection_detector.py
│       ├── attack_generator.py
│       └── proxy_monitor.py
├── results/                 # Detection reports
├── main.py                  # Main orchestrator
├── requirements.txt         # Python dependencies
├── README.md               # Project documentation
└── .gitignore              # Files to ignore

```
---

## 🚀 Installation

### Prerequisites

- Python 3.11+
- XAMPP (Apache + MySQL)
- Git

### Setup

1. Clone the repository:
```sh
git clone https://github.com/megha-ranjith/sqlpsdem-project.git
cd sqlpsdem-project
```

2. Install Python dependencies:
```sh
pip install -r requirements.txt
```

3. Setup vulnerable web application:
   Copy vulnerable_app to XAMPP htdocs
```sh
cp -r vulnerable_app C:\xampp\htdocs\
```
   
4. Import database:
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create database: `sqlpsdem_test`
   - Import: `vulnerable_app/database_setup.sql`

6. Start XAMPP:
   - Start Apache
   - Start MySQL
---

## 💻 Usage

### Run Detection System
```sh
python main.py
```
### Test Vulnerable Web App

1. Open browser: http://localhost/vulnerable_app/
2. Normal login: `admin` / `admin123`
3. SQL injection test: `admin' #` / `anything`
---

## 📊 Results

The system generates:
- `static_analysis_report.txt` - SQL statements found
- `vulnerabilities_found.txt` - Detailed vulnerability list
- `attack_logs.txt` - Attack attempt logs
- `SUMMARY_REPORT.txt` - Detection statistics

### Sample Output
✓ Found 6 SQL statements

✓ Generated 10 attack payloads

✓ Detected 5 injections 

✓ Total Vulnerabilities: 8 (6 first-order, 2 second-order)

---

## 🧪 Testing

### Manual Testing

1. **Login Page**: http://localhost/vulnerable_app/
   - Test: `admin' OR '1'='1` / `anything`

2. **Search Page**: http://localhost/vulnerable_app/search.php
   - Test: `' OR '1'='1`

3. **Comments Page**: http://localhost/vulnerable_app/comments.php
   - Test second-order injection
---

## 🔐 Prevention

The system demonstrates both detection and prevention:
- Parameterized queries (Prepared Statements)
- Input validation
- Output escaping
---

## 📖 IEEE Paper Reference

Based on: "SQLPsdem: A Proxy-Based Mechanism Towards Detecting, Locating and Preventing Second-Order SQL Injection"

---

## 👤 Author

**Megha Ranjith**
- GitHub: [@megha-ranjith](https://github.com/megha-ranjith)
- Institution: MAR ATHANASIUS COLLEGE OF ENGINEERING
- ---

## 📄 License

This project is for educational purposes only.
---

## ⚠️ Disclaimer

The vulnerable web application is intentionally insecure for testing purposes. **DO NOT deploy to production environments.**
=======
# sqlpsdem-project
SQLPsdem: Proxy-Based Detection and Prevention of Second-Order SQL Injections
>>>>>>> 3c6b61d1cba6df7515a414bbda53fe15aed15f7c
