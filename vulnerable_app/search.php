<?php
include 'config.php';

$results = [];
$search = '';

if(isset($_GET['search'])) {
    $search = $_GET['search'];
    
    // VULNERABLE - Direct injection
    $query = "SELECT * FROM products WHERE product_name LIKE '%{$search}%'";
    
    $result = $conn->query($query);
    
    if($result) {
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Products</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f5f5f5; }
        .box { background: white; padding: 30px; max-width: 800px; margin: 0 auto; border-radius: 10px; }
        input { padding: 10px; width: 400px; font-size: 16px; }
        button { padding: 10px 20px; background: #667eea; color: white; border: none; cursor: pointer; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #667eea; color: white; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Product Search</h1>
        
        <p><strong>Try:</strong> <code>laptop</code> or <code>' OR 1=1--</code></p>
        
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search...">
            <button>Search</button>
        </form>
        
        <?php if(isset($_GET['search'])): ?>
            <h3>Results: <?php echo count($results); ?> found</h3>
            
            <?php if(count($results) > 0): ?>
                <table>
                    <tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr>
                    <?php foreach($results as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo $p['product_name']; ?></td>
                            <td>$<?php echo $p['price']; ?></td>
                            <td><?php echo $p['stock']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        <?php endif; ?>
        
        <p style="margin-top: 30px
