import requests
from bs4 import BeautifulSoup
from urllib.parse import urljoin

class SQLInjectionDetector:
    def __init__(self, base_url):
        self.base_url = base_url
        self.session = requests.Session()
        self.session.headers["User-Agent"] = (
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
            "AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36"
        )
        # Error messages from MySQL, SQL Server, Oracle, etc.
        self.error_messages = [
            "you have an error in your sql syntax;",
            "warning: mysql",
            "unclosed quotation mark after the character string",
            "quoted string not properly terminated",
            "syntax error",
        ]

    def get_forms(self, url):
        res = self.session.get(url)
        soup = BeautifulSoup(res.content, "html.parser")
        return soup.find_all("form")

    def form_details(self, form):
        details = {}
        action = form.attrs.get("action", "").lower()
        method = form.attrs.get("method", "get").lower()
        inputs = []
        for input_tag in form.find_all("input"):
            input_type = input_tag.attrs.get("type", "text")
            input_name = input_tag.attrs.get("name")
            input_value = input_tag.attrs.get("value", "")
            inputs.append({
                "type": input_type,
                "name": input_name,
                "value": input_value
            })
        details["action"] = action
        details["method"] = method
        details["inputs"] = inputs
        return details

    def is_vulnerable(self, response):
        content = response.content.decode(errors="ignore").lower()
        for error in self.error_messages:
            if error in content:
                return True
        return False

    def test_form(self, url, form):
        form_details = self.form_details(form)
        test_chars = ["'", '"']
        is_vuln = False
        for char in test_chars:
            data = {}
            for input_tag in form_details["inputs"]:
                if not input_tag["name"]:
                    continue
                # Add the test char at the end of values or set as value
                if input_tag["type"] == "hidden" or input_tag["value"]:
                    data[input_tag["name"]] = input_tag["value"] + char
                elif input_tag["type"] != "submit":
                    data[input_tag["name"]] = f"test{char}"
            form_url = urljoin(url, form_details["action"])
            if form_details["method"] == "post":
                res = self.session.post(form_url, data=data)
            else:
                res = self.session.get(form_url, params=data)
            if self.is_vulnerable(res):
                print(f"[+] SQL Injection vulnerability detected in form at {form_url}")
                is_vuln = True
            else:
                print(f"[-] No vulnerability detected for payload {char} at {form_url}")
        return is_vuln

    def scan(self):
        print(f"[INFO] Scanning {self.base_url} for SQL injection vulnerabilities...")
        forms = self.get_forms(self.base_url)
        print(f"[INFO] Found {len(forms)} forms.")
        found = False
        for idx, form in enumerate(forms):
            print(f"[INFO] Testing form {idx+1}...")
            if self.test_form(self.base_url, form):
                found = True
        if found:
            print("[!] Scan finished: vulnerabilities found!")
        else:
            print("[✓] Scan finished: no vulnerabilities detected.")

if __name__ == "__main__":
    # Example usage: python injection_detector.py http://localhost/vulnerable_app/login.php
    import sys
    if len(sys.argv) >= 2:
        url = sys.argv[1]
    else:
        url = "http://localhost/vulnerable_app/login.php"
    scanner = SQLInjectionDetector(url)
    scanner.scan()
