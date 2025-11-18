<?php
include 'config.php';
$results = array();
$search_term = '';

if(isset($_GET['search'])) {
    $search_term = $_GET['search'];
    
    // VULNERABLE: Direct string concatenation
    $sql = "SELECT * FROM products WHERE product_name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";
    
    error_log("SEARCH_QUERY: " . $sql);
    
    $result = $conn->query($sql);
    if($result === false) {
        error_log("SQL ERROR: " . $conn->error . " | Query: " . $sql);
    } else {
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <style>
        body { 
            font-family: Arial; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .search-box { 
            margin-bottom: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        input { 
            padding: 10px; 
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        table { 
            border-collapse: collapse; 
            width: 100%; 
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background: #667eea; 
            color: white; 
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }
        a, a:visited {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="color: white; text-align: center;">Product Search</h1>
        
        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Search products...">
                <button type="submit">Search</button>
            </form>
        </div>

        <?php if(count($results) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Description</th>
                </tr>
                <?php foreach($results as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock']; ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif(isset($_GET['search'])): ?>
            <p style="color: white; text-align: center;">No products found.</p>
        <?php endif; ?>

        <p style="text-align: center;"><a href="index.php" style="background: #667eea; padding: 10px 20px; border-radius: 5px;">Back to Home</a></p>
    </div>
</body>
</html>
