<?php
$log_file = 'second_order_detection.log';
$logs = [];

if(file_exists($log_file)) {
    $content = file_get_contents($log_file);
    $logs = array_filter(explode("\n", $content));
}

$stage1_count = 0;
$stage2_count = 0;

foreach($logs as $log) {
    if(strpos($log, 'STAGE-1-DETECTED') !== false) $stage1_count++;
    if(strpos($log, 'STAGE-2-DETECTED') !== false) $stage2_count++;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Second-Order Detection Logs</title>
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
            max-width: 1000px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h1 { color: #333; text-align: center; }
        .stats {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }
        .stat-box {
            flex: 1;
            background: #e7f3ff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #667eea;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        .log-entry {
            background: #f9f9f9;
            padding: 12px;
            margin: 10px 0;
            border-left: 4px solid #667eea;
            border-radius: 3px;
            font-family: monospace;
            font-size: 13px;
        }
        .log-entry.stage1 {
            border-left-color: #dc3545;
        }
        .log-entry.stage2 {
            border-left-color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔍 Second-Order SQL Injection Detection Logs</h1>
        
        <div class="stats">
            <div class="stat-box">
                <div class="stat-number"><?php echo $stage1_count; ?></div>
                <div>Stage 1 Detections<br><small>(Malicious input stored)</small></div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $stage2_count; ?></div>
                <div>Stage 2 Detections<br><small>(Stored payload retrieved)</small></div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo count($logs); ?></div>
                <div>Total Events Logged</div>
            </div>
        </div>
        
        <h2>Detection Log Entries:</h2>
        
        <?php if(count($logs) > 0): ?>
            <?php foreach(array_reverse($logs) as $log): ?>
                <?php 
                $class = '';
                if(strpos($log, 'STAGE-1') !== false) $class = 'stage1';
                if(strpos($log, 'STAGE-2') !== false) $class = 'stage2';
                ?>
                <div class="log-entry <?php echo $class; ?>">
                    <?php echo htmlspecialchars($log); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #666;">No detection events logged yet.</p>
        <?php endif; ?>
        
        <p style="text-align: center; margin-top: 40px;">
            <a href="comments.php" style="color: #667eea; text-decoration: none; font-weight: bold;">← Back to Comments</a>
        </p>
    </div>
</body>
</html>
