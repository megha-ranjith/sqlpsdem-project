<?php
session_start();
include 'config.php';

$message = '';
$comments = [];
$detected_stage1 = false;
$detected_stage2 = false;

// DETECTION FUNCTION
function detectInjection($input) {
    $patterns = ["OR", "or", "'", '"', "--", "#", "UNION", "union", "SELECT", "select", "DROP", "drop"];
    
    foreach($patterns as $pattern) {
        if(stripos($input, $pattern) !== false) {
            return true;
        }
    }
    return false;
}

// STAGE 1: Add comment
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $user_id = 1;
    $comment = $_POST['comment'];
    
    // DETECT malicious patterns
    if(detectInjection($comment)) {
        $detected_stage1 = true;
        $log = date('Y-m-d H:i:s') . " | STAGE-1 | " . $comment . "\n";
        file_put_contents('second_order_detection.log', $log, FILE_APPEND);
    }
    
    // Store comment
    $sql = "INSERT INTO comments (user_id, comment_text) VALUES ($user_id, '$comment')";
    if($conn->query($sql)) {
        if(!$detected_stage1) {
            $message = "✓ Comment added successfully!";
        }
    }
}

// STAGE 2: View comments
if(isset($_GET['user_id'])) {
    $uid = $_GET['user_id'];
    $sql = "SELECT id, comment_text, created_date FROM comments WHERE user_id = $uid";
    $result = $conn->query($sql);
    
    if($result) {
        while($row = $result->fetch_assoc()) {
            $comments[] = $row;
            if(detectInjection($row['comment_text'])) {
                $detected_stage2 = true;
                $log = date('Y-m-d H:i:s') . " | STAGE-2 | ID:" . $row['id'] . "\n";
                file_put_contents('second_order_detection.log', $log, FILE_APPEND);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Comments - Second-Order Detection</title>
    <style>
        body { 
            font-family: Arial; 
            padding: 30px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .box {
            background: white;
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #333; 
            text-align: center; 
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        h2 { color: #667eea; margin-top: 30px; }
        
        /* DETECTION ALERTS */
        .detection-box {
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            font-size: 16px;
            border: 3px solid;
        }
        
        .stage1-alert {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }
        
        .stage2-alert {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .info {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-family: Arial;
            margin: 10px 0;
            font-size: 14px;
        }
        
        input {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            width: 200px;
            margin-right: 10px;
        }
        
        button {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover { background: #764ba2; }
        
        .comment {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
            border-radius: 5px;
        }
        
        .comment.suspicious {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #ffc107;
            color: #000;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 10px;
            font-weight: bold;
        }
        
        hr {
            border: none;
            border-top: 2px solid #eee;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>💬 Comments System</h1>
        <p class="subtitle">🔍 Second-Order SQL Injection Detection</p>
        
        <!-- STAGE 1 DETECTION ALERT -->
        <?php if($detected_stage1): ?>
            <div class="detection-box stage1-alert">
                <strong style="font-size: 20px;">🚨 STAGE 1 DETECTION!</strong><br><br>
                SQL injection pattern detected in your comment input!<br>
                The malicious payload has been stored in the database and logged for monitoring.
            </div>
        <?php endif; ?>
        
        <!-- STAGE 2 DETECTION ALERT -->
        <?php if($detected_stage2): ?>
            <div class="detection-box stage2-alert">
                <strong style="font-size: 20px;">⚠️ STAGE 2 DETECTION!</strong><br><br>
                Retrieved comment(s) contain SQL injection patterns!<br>
                This demonstrates second-order attack where stored malicious data is retrieved.
            </div>
        <?php endif; ?>
        
        <!-- SUCCESS MESSAGE (only if no detection) -->
        <?php if($message && !$detected_stage1): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="info">
            <strong>📚 Second-Order SQL Injection Demo:</strong><br><br>
            <strong>Stage 1:</strong> Malicious input (e.g., <code>admin' OR '1'='1</code>) is stored in DB<br>
            <strong>Stage 2:</strong> When retrieved, the stored data executes in a new query context<br><br>
            <strong>Try:</strong> Add <code>admin' OR '1'='1</code> then view User ID 1
        </div>
        
        <h2>Stage 1: Add Comment</h2>
        <form method="POST">
            <textarea name="comment" rows="4" placeholder="Enter your comment..." required></textarea>
            <button type="submit">Post Comment</button>
        </form>
        
        <hr>
        
        <h2>Stage 2: View Comments</h2>
        <form method="GET">
            <input type="number" name="user_id" placeholder="User ID (try 1)" value="1">
            <button type="submit">View Comments</button>
        </form>
        
        <?php if(isset($_GET['user_id'])): ?>
            <h3>Comments for User ID: <?php echo htmlspecialchars($_GET['user_id']); ?></h3>
            
            <?php if(count($comments) > 0): ?>
                <?php foreach($comments as $c): ?>
                    <?php $is_suspicious = detectInjection($c['comment_text']); ?>
                    <div class="comment <?php echo $is_suspicious ? 'suspicious' : ''; ?>">
                        <strong>Comment #<?php echo $c['id']; ?>:</strong>
                        <?php if($is_suspicious): ?>
                            <span class="badge">⚠️ MALICIOUS</span>
                        <?php endif; ?>
                        <br>
                        <?php echo htmlspecialchars($c['comment_text']); ?>
                        <div style="color: #666; font-size: 12px; margin-top: 8px;">
                            <?php echo $c['created_date']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="comment">No comments found.</div>
            <?php endif; ?>
        <?php endif; ?>
        
        <p style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="color: #667eea; font-weight: bold; text-decoration: none;">← Back to Home</a> | 
            <a href="second_order_logs.php" style="color: #667eea; font-weight: bold; text-decoration: none;">View Logs 📊</a>
        </p>
    </div>
</body>
</html>
