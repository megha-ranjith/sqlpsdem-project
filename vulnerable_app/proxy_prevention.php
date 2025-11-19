<?php
class SQLProxy {
    public function logQuery($query, $blocked = false) {
        $log = date('Y-m-d H:i:s') . " | " . ($blocked ? "BLOCKED" : "ALLOWED") . " | $query\n";
        file_put_contents('proxy_log.txt', $log, FILE_APPEND);
    }
    
    public function getBlockedCount() {
        if(!file_exists('proxy_log.txt')) return 0;
        $content = file_get_contents('proxy_log.txt');
        return substr_count($content, 'BLOCKED');
    }
}
?>
