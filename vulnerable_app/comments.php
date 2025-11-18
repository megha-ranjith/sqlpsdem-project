<?php
session_start();
include 'config.php';

$message = '';
$comments = array();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
    $comment = $_POST['comment'];
    
    $sql_insert = "INSERT INTO comments (user_id, comment_text) VALUES (" . $user_id . ", '" . $comment . "')";
    error_log("INSERT_QUERY: " . $sql_insert);
    
    $result = $conn->query($sql_insert);
    if($result === false) {
        error_log("SQL ERROR: " . $conn->error);
    } else {
        $message = "Comment added successfully!";
    }
}

if(isset($_GET['user_id'])) {
    $uid = $_GET['user_id'];
    
    // VULNERABLE: Direct concatenation
    $sql_select = "SELECT u.username, c.comment_text FROM comments c JOIN users u ON c.user_id = u.id WHERE u.id = " . $uid;
    
    error_log("SELECT_COMMENTS: " . $sql_select);
    
    $result = $conn->query($sql_select);
    if($result === false) {
        error_log("SQL ERROR: " . $conn->error);
    } else {
        while($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Comments</title>
    <style>
        body { 
            font-family: Arial; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .form-group { 
            margin-bottom: 15px; 
        }
        textarea { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: Arial;
        }
        button { 
            padding: 10px 20px; 
            background: #667eea; 
            color: white; 
            border: none; 
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #764ba2;
        }
        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .comment { 
            background: #f9f9f9; 
            padding: 10px; 
            margin: 10px 0; 
            border-left: 4px solid #667eea; 
            border-radius: 3px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
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
    <div class="container">
        <h1>Comments System</h1>
        
        <?php if($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <h2>Add Comment</h2>
        <form method="POST">
            <div class="form-group">
                <textarea name="comment" rows="5" placeholder="Enter your comment..." required></textarea>
            </div>
            <button type="submit">Post Comment</button>
        </form>
        
        <hr>
        
        <h2>View Comments by User ID</h2>
        <form method="GET">
            <input type="number" name="user_id" placeholder="Enter user ID" required>
            <button type="submit">View Comments</button>
        </form>
        
        <?php if(isset($comments) && count($comments) > 0): ?>
            <h3>Comments:</h3>
            <?php foreach($comments as $c): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($c['username']); ?>:</strong><br>
                    <?php echo htmlspecialchars($c['comment_text']); ?>
                </div>
            <?php endforeach; ?>
        <?php elseif(isset($_GET['user_id'])): ?>
            <p>No comments found.</p>
        <?php endif; ?>
        
        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
