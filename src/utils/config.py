# config.py - Configuration Settings

import os
from datetime import datetime

# Project Paths
PROJECT_ROOT = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
DATA_DIR = os.path.join(PROJECT_ROOT, 'data')
RESULTS_DIR = os.path.join(PROJECT_ROOT, 'results')
WEBAPP_DIR = os.path.join(DATA_DIR, 'test_webapp')
PAYLOADS_DIR = os.path.join(DATA_DIR, 'attack_payloads')

# Create directories if they don't exist
for directory in [DATA_DIR, RESULTS_DIR, WEBAPP_DIR, PAYLOADS_DIR]:
    os.makedirs(directory, exist_ok=True)

# Database Configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'sqlpsdem_test'
}

# PHP Application Configuration
PHP_APP_URL = 'http://localhost/vulnerable_app'
LOGIN_ENDPOINT = 'http://localhost/vulnerable_app/login.php'
SEARCH_ENDPOINT = 'http://localhost/vulnerable_app/search.php'
COMMENTS_ENDPOINT = 'http://localhost/vulnerable_app/comments.php'

# SQL Injection Attack Patterns (7 Types from IEEE Paper)
ATTACK_PATTERNS = {
    '1': 'Tautologies',           # or 1=1, or 'a'='a'
    '2': 'Union Queries',          # UNION SELECT
    '3': 'Piggy-Backed Queries',   # ; DROP TABLE
    '4': 'Inference',              # SLEEP, IF conditions
    '5': 'Alternate Encodings',    # HEX, CHAR encoding
    '6': 'Illegal Queries',        # Error-based injection
    '7': 'Stored Procedures'       # CALL procedures
}

# Detection Rules
DETECTION_RULES = {
    'rule1': r"(\bor\b|\band\b).*(\d+\s*[=<>]|\s+1\s*[=<>]|\s*'.*')",
    'rule2': r"\bunion\b.*\bselect\b",
    'rule3': r"[;]\s*(\bdrop\b|\bdelete\b|\bshutdown\b|\binsert\b)",
    'rule4': r"['\"].*?['\"]",
    'rule5': r"0x[0-9a-f]+",
    'rule6': r"(sleep|benchmark|pg_sleep)\s*\(",
    'rule7': r"\bcall\b\s+\w+\s*\("
}

# Log file paths
LOG_FILE = os.path.join(RESULTS_DIR, 'detection.log')
VULN_REPORT = os.path.join(RESULTS_DIR, 'vulnerabilities_found.txt')
ATTACK_LOG = os.path.join(RESULTS_DIR, 'attack_logs.txt')

# Timestamp for logging
TIMESTAMP = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
