import requests
import sys
from bs4 import BeautifulSoup

class AdminDashboardTester:
    def __init__(self, base_url="http://localhost:8080"):
        self.base_url = base_url
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0

    def run_test(self, name, url_path, expected_elements=None, expected_title=None):
        """Run a single page test"""
        url = f"{self.base_url}/{url_path}"
        self.tests_run += 1
        print(f"\n🔍 Testing {name} at {url}...")
        
        try:
            response = self.session.get(url)
            
            if response.status_code != 200:
                print(f"❌ Failed - Status: {response.status_code}")
                return False
            
            soup = BeautifulSoup(response.text, 'html.parser')
            
            # Check title if provided
            if expected_title:
                title = soup.title.string if soup.title else ""
                if expected_title not in title:
                    print(f"❌ Failed - Title mismatch. Expected '{expected_title}' in '{title}'")
                    return False
            
            # Check for expected elements
            if expected_elements:
                for element_type, element_text in expected_elements:
                    elements = soup.find_all(element_type, string=lambda text: element_text in text if text else False)
                    if not elements:
                        print(f"❌ Failed - Element '{element_type}' with text '{element_text}' not found")
                        return False
            
            self.tests_passed += 1
            print(f"✅ Passed - Page loaded successfully")
            return True
            
        except Exception as e:
            print(f"❌ Failed - Error: {str(e)}")
            return False

    def test_navigation_links(self, url_path, expected_links):
        """Test navigation links on a page"""
        url = f"{self.base_url}/{url_path}"
        self.tests_run += 1
        print(f"\n🔍 Testing navigation links on {url}...")
        
        try:
            response = self.session.get(url)
            
            if response.status_code != 200:
                print(f"❌ Failed - Status: {response.status_code}")
                return False
            
            soup = BeautifulSoup(response.text, 'html.parser')
            
            # Check for expected links
            for link_text, link_href in expected_links:
                links = soup.find_all('a', href=lambda href: href and link_href in href)
                found = False
                for link in links:
                    if link_text in link.text:
                        found = True
                        break
                
                if not found:
                    print(f"❌ Failed - Link '{link_text}' with href containing '{link_href}' not found")
                    return False
            
            self.tests_passed += 1
            print(f"✅ Passed - All navigation links found")
            return True
            
        except Exception as e:
            print(f"❌ Failed - Error: {str(e)}")
            return False

def main():
    # Setup
    tester = AdminDashboardTester("http://localhost:8080")
    
    # Test main dashboard
    tester.run_test(
        "Main Dashboard",
        "admin/dashboard.php",
        expected_elements=[
            ('h1', 'Admin Dashboard'),
            ('h3', 'Total Articles'),
            ('h3', 'Total Users'),
            ('h2', 'Recent Articles')
        ],
        expected_title="Trusticle Admin"
    )
    
    # Test user management page
    tester.run_test(
        "User Management",
        "admin/users.php",
        expected_elements=[
            ('h1', 'User Management'),
            ('th', 'Username'),
            ('th', 'Email')
        ],
        expected_title="Trusticle Admin"
    )
    
    # Test article management page
    tester.run_test(
        "Article Management",
        "admin/articles.php",
        expected_elements=[
            ('h1', 'Article Management'),
            ('th', 'Title'),
            ('th', 'Author')
        ],
        expected_title="Trusticle Admin"
    )
    
    # Test activity logs page
    tester.run_test(
        "Activity Logs",
        "admin/activity_logs.php",
        expected_elements=[
            ('h1', 'Activity Logs'),
            ('th', 'User'),
            ('th', 'Action')
        ],
        expected_title="Trusticle Admin"
    )
    
    # Test settings page
    tester.run_test(
        "Settings",
        "admin/settings.php",
        expected_elements=[
            ('h1', 'Settings'),
            ('h2', 'Site Settings')
        ],
        expected_title="Trusticle Admin"
    )
    
    # Test navigation links
    tester.test_navigation_links(
        "admin/dashboard.php",
        [
            ('Dashboard', 'dashboard.php'),
            ('Articles', 'articles.php'),
            ('Users', 'users.php'),
            ('Activity Logs', 'activity_logs.php'),
            ('Settings', 'settings.php#site-settings')
        ]
    )
    
    # Print results
    print(f"\n📊 Tests passed: {tester.tests_passed}/{tester.tests_run}")
    return 0 if tester.tests_passed == tester.tests_run else 1

if __name__ == "__main__":
    sys.exit(main())