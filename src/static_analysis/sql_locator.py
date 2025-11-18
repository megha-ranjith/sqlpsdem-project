# sql_locator.py - Locates SQL statements in PHP code

import os
import re
from pathlib import Path

class SQLLocator:
    """Locates SQL statements in PHP files"""
    
    # MySQL execution functions
    PHP_EXECUTION_FUNCTIONS = [
        'mysqli_query', 'mysql_query', 'mysqli_real_query',
        'mysqli_multi_query', 'mysql_db_query', '$conn->query',
        '$conn->execute', 'PDOStatement::execute'
    ]
    
    def __init__(self, webapp_path):
        self.webapp_path = webapp_path
        self.sql_statements = []
        self.file_mappings = {}
    
    def find_all_sql_statements(self):
        """Find all SQL statements in PHP files"""
        
        print(f"[STATIC ANALYSIS] Scanning PHP files in: {self.webapp_path}")
        
        if not os.path.exists(self.webapp_path):
            print(f"[WARNING] Path does not exist: {self.webapp_path}")
            return []
        
        for php_file in Path(self.webapp_path).glob('**/*.php'):
            self._scan_php_file(str(php_file))
        
        print(f"[STATIC ANALYSIS] Found {len(self.sql_statements)} SQL statements")
        return self.sql_statements
    
    def _scan_php_file(self, file_path):
        """Scan a single PHP file for SQL statements"""
        
        try:
            with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
                lines = content.split('\n')
            
            # Pattern to find SQL queries
            # Matches strings with SELECT, INSERT, UPDATE, DELETE, etc.
            sql_pattern = r"(['\"])((?:SELECT|INSERT|UPDATE|DELETE|CREATE|DROP|CALL|TRUNCATE).*?)\1"
            
            for i, line in enumerate(lines, 1):
                for match in re.finditer(sql_pattern, line, re.IGNORECASE | re.DOTALL):
                    sql_stmt = match.group(2)
                    
                    sql_entry = {
                        'file': file_path,
                        'line': i,
                        'query': sql_stmt,
                        'raw_line': line.strip()
                    }
                    
                    self.sql_statements.append(sql_entry)
                    
                    if file_path not in self.file_mappings:
                        self.file_mappings[file_path] = []
                    self.file_mappings[file_path].append(i)
                    
                    print(f"  [SQL FOUND] {file_path}:{i} - {sql_stmt[:50]}...")
        
        except Exception as e:
            print(f"[ERROR] Scanning {file_path}: {str(e)}")
    
    def get_injection_points(self, query):
        """Extract potential injection points from SQL query"""
        
        # Find all variables (patterns like $var, $_GET, $_POST, etc.)
        var_pattern = r"(\$\w+|\$_GET\[\s*['\"]?\w+['\"]?\s*\]|\$_POST\[\s*['\"]?\w+['\"]?\s*\]|\$_SESSION\[\s*['\"]?\w+['\"]?\s*\]|\$_COOKIE\[\s*['\"]?\w+['\"]?\s*\])"
        
        variables = re.findall(var_pattern, query)
        return list(set(variables))
    
    def generate_report(self):
        """Generate static analysis report"""
        
        report = f"""
{'='*80}
STATIC ANALYSIS REPORT
{'='*80}
Total PHP files scanned: {len(self.file_mappings)}
Total SQL statements found: {len(self.sql_statements)}

SQL STATEMENTS FOUND:
{'='*80}
"""
        
        for i, sql in enumerate(self.sql_statements, 1):
            injection_points = self.get_injection_points(sql['query'])
            report += f"\n{i}. FILE: {sql['file']}:{sql['line']}\n"
            report += f"   QUERY: {sql['query']}\n"
            report += f"   INJECTION POINTS: {injection_points}\n"
        
        report += f"\n{'='*80}\n"
        return report
