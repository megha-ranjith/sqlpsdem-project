<?php
// config.php - Database Configuration

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sqlpsdem_test');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Disable error display (we'll handle errors manually)
mysqli_report(MYSQLI_REPORT_OFF);

// DISABLE AUTOMATIC ESCAPING - we want raw, vulnerable SQL!
$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

// Check if magic_quotes_gpc is enabled and disable if needed
if (function_exists('get_magic_quotes_gpc')) {
    if (get_magic_quotes_gpc()) {
        $_GET = array_map('stripslashes', $_GET);
        $_POST = array_map('stripslashes', $_POST);
        $_COOKIE = array_map('stripslashes', $_COOKIE);
    }
}

require_once 'proxy_prevention.php';

// Override query method to use proxy
class ProxyConnection extends mysqli {
    private $proxy;
    
    public function __construct($host, $user, $pass, $db) {
        parent::__construct($host, $user, $pass, $db);
        global $proxy;
        $this->proxy = $proxy;
    }
    
    public function query($query, $resultmode = MYSQLI_STORE_RESULT) {
        // Validate through proxy BEFORE execution
        if(!$this->proxy->validateQuery($query, 'WEB_APP')) {
            // Query blocked by proxy
            error_log("PROXY BLOCKED: " . $query);
            return false;
        }
        
        // Query allowed - execute normally
        return parent::query($query, $resultmode);
    }
}

// Replace connection with proxy-enabled connection
$conn = new ProxyConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset("utf8");
mysqli_report(MYSQLI_REPORT_OFF);
?>


