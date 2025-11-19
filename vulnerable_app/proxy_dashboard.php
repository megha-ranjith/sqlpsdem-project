<!DOCTYPE html>
<html>
<head>
    <title>Proxy Dashboard</title>
    <style>
        body { 
            font-family: Arial; 
            padding: 30px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }
        .box {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #333; 
            text-align: center;
            margin-bottom: 40px;
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
            color: white;
        }
        .stat-number {
            font-size: 80px;
            font-weight: bold;
            margin: 20px 0;
        }
        .stat-label {
            font-size: 22px;
            margin-top: 10px;
        }
        .info-box {
            background: #d4edda;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #28a745;
        }
        .info-box h3 {
            margin: 0 0 15px 0;
            color: #155724;
        }
        .info-box p {
            margin: 8px 0;
            color: #155724;
            font-size: 15px;
        }
        a {
            display: inline-block;
            text-align: center;
            margin-top: 30px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }
        a:hover {
            text-decoration: underline;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>🛡️ Proxy-Based Prevention Dashboard</h1>
        
        <div class="stat-box">
            <div style="font-size: 18px; opacity: 0.9;">SQL Injection Attempts Detected</div>
            <div class="stat-number">
                <?php
                $count = 0;
                if(file_exists('second_order_detection.log')) {
                    $content = file_get_contents('second_order_detection.log');
                    $count = substr_count($content, 'STAGE');
                }
                if(file_exists('log.txt')) {
                    $content = file_get_contents('log.txt');
                    $count += substr_count($content, 'STAGE');
                }
                echo $count;
                ?>
            </div>
            <div class="stat-label">Total Events Logged</div>
        </div>
        
        <div class="info-box">
            <h3>✓ Proxy-Based Monitoring Active</h3>
            <p><strong>How it works:</strong></p>
            <p>• Intercepts all SQL queries before execution</p>
            <p>• Detects malicious patterns in real-time</p>
            <p>• Logs both first-order and second-order attacks</p>
            <p>• Provides comprehensive attack prevention</p>
        </div>
        
        <div style="background: #e7f3ff; padding: 25px; border-radius: 10px; border-left: 5px solid #667eea;">
            <h3 style="margin: 0 0 15px 0; color: #004085;">📊 Detection Coverage</h3>
            <p style="margin: 8px 0; color: #004085; font-size: 15px;">✓ Tautologies (OR 1=1)</p>
            <p style="margin: 8px 0; color: #004085; font-size: 15px;">✓ Union Queries (UNION SELECT)</p>
            <p style="margin: 8px 0; color: #004085; font-size: 15px;">✓ Comment Injection (-- and #)</p>
            <p style="margin: 8px 0; color: #004085; font-size: 15px;">✓ Second-Order Attacks</p>
        </div>
        
        <div class="center">
            <a href="dashboard.php">← Back to Dashboard</a> | 
            <a href="second_order_logs.php">View Detailed Logs →</a>
        </div>
    </div>
</body>
</html>
