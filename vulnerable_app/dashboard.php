<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - SQLPsdem</title>
    <style>
        body { 
            font-family: Arial; 
            padding: 0;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: rgba(0,0,0,0.2);
            padding: 20px;
            color: white;
            text-align: center;
        }
        .navbar h2 {
            margin: 0;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .welcome {
            text-align: center;
            margin-bottom: 40px;
        }
        .welcome h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            color: white;
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .card h3 {
            margin-bottom: 15px;
            font-size: 20px;
        }
        .card p {
            margin-bottom: 20px;
            opacity: 0.9;
        }
        .card a {
            display: inline-block;
            padding: 10px 20px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .card a:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>🛡️ SQLPsdem Dashboard</h2>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h1>Welcome to SQLPsdem System</h1>
            <p style="color: #666;">SQL Injection Detection & Prevention Dashboard</p>
        </div>
        
        <div class="cards">
            <div class="card">
                <h3>🔍 Search Products</h3>
                <p>Test first-order SQL injection</p>
                <a href="search.php">Open Search</a>
            </div>
            
            <div class="card">
                <h3>💬 Comments</h3>
                <p>Test second-order SQL injection</p>
                <a href="comments2.php">Open Comments</a>
            </div>
            
            <div class="card">
                <h3>🛡️ Proxy Dashboard</h3>
                <p>View detection statistics</p>
                <a href="proxy_dashboard.php">View Stats</a>
            </div>
            
            <div class="card">
                <h3>📊 Detection Logs</h3>
                <p>View all logged attacks</p>
                <a href="second_order_logs.php">View Logs</a>
            </div>
        </div>
        
        <p style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="color: #667eea; text-decoration: none; font-weight: bold;">← Back to Login</a>
        </p>
    </div>
</body>
</html>
