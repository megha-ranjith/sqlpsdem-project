# proxy_monitor.py - Simulates MySQL proxy monitoring

import json

class ProxyMonitor:
    """Simulates MySQL-Proxy behavior"""
    
    def __init__(self):
        self.captured_queries = []
        self.blocked_queries = []
    
    def capture_query(self, query, source='WEB_APP', timestamp=None):
        """Capture an SQL query"""
        
        import datetime
        if timestamp is None:
            timestamp = datetime.datetime.now().isoformat()
        
        query_log = {
            'timestamp': timestamp,
            'query': query,
            'source': source
        }
        
        self.captured_queries.append(query_log)
        return query_log
    
    def block_query(self, query, reason):
        """Block a malicious query"""
        
        import datetime
        blocked_log = {
            'timestamp': datetime.datetime.now().isoformat(),
            'query': query,
            'reason': reason,
            'action': 'BLOCKED'
        }
        
        self.blocked_queries.append(blocked_log)
        return blocked_log
    
    def get_statistics(self):
        """Get proxy statistics"""
        
        return {
            'total_queries_captured': len(self.captured_queries),
            'total_queries_blocked': len(self.blocked_queries),
            'total_queries_allowed': len(self.captured_queries) - len(self.blocked_queries)
        }
