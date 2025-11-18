# logger.py - Logging Utilities

import os
from datetime import datetime
from config import LOG_FILE, VULN_REPORT, ATTACK_LOG

class SQLPsdemLogger:
    """Logger for SQLPsdem Detection System"""
    
    def __init__(self):
        self.vulnerabilities = []
        self.attack_logs = []
        self.ensure_log_files()
    
    def ensure_log_files(self):
        """Create log files if they don't exist"""
        for log_file in [LOG_FILE, VULN_REPORT, ATTACK_LOG]:
            os.makedirs(os.path.dirname(log_file), exist_ok=True)
            if not os.path.exists(log_file):
                with open(log_file, 'w') as f:
                    f.write(f"Log created: {datetime.now()}\n\n")
    
    def log_vulnerability(self, vuln_type, location, query, injection_point, 
                         injection_source, attack_pattern, file_path, line_no):
        """Log a detected vulnerability"""
        
        vuln_entry = {
            'timestamp': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'type': vuln_type,
            'location': location,
            'query': query,
            'injection_point': injection_point,
            'source': injection_source,
            'pattern': attack_pattern,
            'file': file_path,
            'line': line_no
        }
        
        self.vulnerabilities.append(vuln_entry)
        
        # Write to file
        with open(VULN_REPORT, 'a') as f:
            f.write(f"\n{'='*80}\n")
            f.write(f"VULNERABILITY DETECTED\n")
            f.write(f"{'='*80}\n")
            f.write(f"Timestamp:        {vuln_entry['timestamp']}\n")
            f.write(f"Type:             {vuln_entry['type']}\n")
            f.write(f"Location:         {vuln_entry['location']}\n")
            f.write(f"SQL Query:        {vuln_entry['query']}\n")
            f.write(f"Injection Point:  {vuln_entry['injection_point']}\n")
            f.write(f"Data Source:      {vuln_entry['source']}\n")
            f.write(f"Attack Pattern:   {vuln_entry['pattern']}\n")
            f.write(f"File:             {vuln_entry['file']}\n")
            f.write(f"Line Number:      {vuln_entry['line']}\n")
            f.write(f"{'='*80}\n")
        
        print(f"[VULN] {vuln_entry['type']} detected in {vuln_entry['location']}")
    
    def log_attack_attempt(self, endpoint, payload, expected_result, actual_result, detected):
        """Log an attack attempt"""
        
        attack_entry = {
            'timestamp': datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
            'endpoint': endpoint,
            'payload': payload,
            'expected': expected_result,
            'actual': actual_result,
            'detected': detected
        }
        
        self.attack_logs.append(attack_entry)
        
        # Write to file
        with open(ATTACK_LOG, 'a') as f:
            f.write(f"\n{'-'*80}\n")
            f.write(f"ATTACK ATTEMPT LOG\n")
            f.write(f"Timestamp:        {attack_entry['timestamp']}\n")
            f.write(f"Endpoint:         {attack_entry['endpoint']}\n")
            f.write(f"Payload:          {attack_entry['payload']}\n")
            f.write(f"Detected:         {'YES' if attack_entry['detected'] else 'NO'}\n")
            f.write(f"{'-'*80}\n")
        
        status = "✓ DETECTED" if detected else "✗ MISSED"
        print(f"[ATTACK] {status} - Endpoint: {endpoint}")
    
    def generate_summary_report(self):
        """Generate summary report of all findings"""
        
        summary = f"""
{'='*80}
SQLPSDEM DETECTION REPORT - SUMMARY
{'='*80}
Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}

VULNERABILITIES DETECTED: {len(self.vulnerabilities)}
ATTACK ATTEMPTS LOGGED: {len(self.attack_logs)}

VULNERABILITY BREAKDOWN:
"""
        
        # Count by type
        type_count = {}
        for vuln in self.vulnerabilities:
            vuln_type = vuln['type']
            type_count[vuln_type] = type_count.get(vuln_type, 0) + 1
        
        for vuln_type, count in type_count.items():
            summary += f"  {vuln_type}: {count}\n"
        
        summary += f"\n{'='*80}\n"
        summary += f"Detailed logs available in:\n"
        summary += f"  - {VULN_REPORT}\n"
        summary += f"  - {ATTACK_LOG}\n"
        summary += f"{'='*80}\n"
        
        print(summary)
        
        with open(os.path.join(os.path.dirname(VULN_REPORT), 'SUMMARY_REPORT.txt'), 'w') as f:
            f.write(summary)
        
        return summary

# Initialize logger
logger = SQLPsdemLogger()
