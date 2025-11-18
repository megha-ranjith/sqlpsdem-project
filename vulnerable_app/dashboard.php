<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { 
            font-family: Arial; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .dashboard { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #333; 
            margin-bottom: 20px;
        }
        .user-info { 
            background: #e7f3ff; 
            padding: 15px; 
            border-radius: 5px; 
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .user-info p {
            margin: 5px 0;
            color: #333;
        }
        strong {
            color: #667eea;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>✅ Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <div class="user-info">
            <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
            <p><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        </div>
        
        <h2>System Information:</h2>
        <p>You have successfully logged in to the SQLPsdem Test Application.</p>
        <p>This application contains intentional SQL injection vulnerabilities for testing detection systems.</p>
        
        <a href="index.php">Logout</a>
    </div>
</body>
</html>
