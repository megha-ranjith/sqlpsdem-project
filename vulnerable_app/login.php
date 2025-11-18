<?php
session_start();
include 'config.php';

$error = null;
$success = false;
$debug_query = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABLE: Direct string concatenation - NO SANITIZATION
    $sql = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'";
    
    $debug_query = $sql; // For debugging
    
    // Log the query
    error_log("QUERY_EXECUTED: " . $sql);
    
    // Execute query
    $result = $conn->query($sql);
    
    if ($result === false) {
        // SQL syntax error
        error_log("SQL ERROR: " . $conn->error . " | Query: " . $sql);
        $error = "SQL Error: " . htmlspecialchars($conn->error);
    } else if ($result->num_rows > 0) {
        // User found - successful login
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['user_role'];
        $success = true;
    } else {
        // Query executed but no user found
        $error = "No user found for this query: " . htmlspecialchars($sql);
    }
}

if ($success) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Result</title>
    <style>
        body { 
            font-family: Arial; 
            text-align: center; 
            padding: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            color: #333;
            padding: 40px;
            border-radius: 10px;
            max-width: 700px;
            margin: 50px auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h2 {
            color: #667eea;
            margin-bottom: 20px;
        }
        .message { 
            padding: 20px; 
            border-radius: 5px;
            margin: 20px 0;
            font-size: 14px;
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 2px solid #f5c6cb;
        }
        .debug {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffc107;
            text-align: left;
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            word-break: break-all;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if($error): ?>
            <h2>❌ Login Failed</h2>
            <div class="message error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
            
            <?php if($debug_query): ?>
                <div class="debug">
                    <strong>DEBUG INFO - Generated SQL Query:</strong><br>
                    <?php echo htmlspecialchars($debug_query); ?>
                </div>
            <?php endif; ?>
            
            <a href="index.php">← Back to Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
