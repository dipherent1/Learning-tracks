<?php
    // --- DATA ---
    $products = [
        ['name' => 'Laptop', 'price' => 1200, 'discount' => 15],
        ['name' => 'Mouse', 'price' => 25, 'discount' => 0],
        ['name' => 'Keyboard', 'price' => 75, 'discount' => 20],
        ['name' => 'Monitor', 'price' => 350, 'discount' => 10],
    ];

    // --- FUNCTIONS ---

    /**
     * Calculates the discounted price of an item.
     *
     * @param float $original_price The starting price.
     * @param int $discount_percent The discount percentage (e.g., 15 for 15%).
     * @return float The final price after the discount.
     */
    function calculateDiscountedPrice($original_price, $discount_percent) {
        $discount_amount = $original_price * ($discount_percent / 100);
        $final_price = $original_price - $discount_amount;
        return $final_price;
    }

    /**
     * Formats a number as a currency string (e.g., $1,200.00).
     *
     * @param float $price The number to format.
     * @return string The formatted currency string.
     */
    function formatPrice($price) {
        return '$' . number_format($price, 2);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product Pricing</title>
    <style>
        body { font-family: sans-serif; }
        .container { max-width: 600px; margin: 50px auto; }
        .product { border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        .product h2 { margin: 0 0 10px; }
        .price .original { text-decoration: line-through; color: #999; }
        .price .final { font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Our Products</h1>

        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?= htmlspecialchars($product['name']); ?></h2>
                <div class="price">
                    <?php
                        // 1. Calculate the final price by calling our function
                        $final_price = calculateDiscountedPrice($product['price'], $product['discount']);
                    ?>

                    <?php if ($product['discount'] > 0): ?>
                        <span class="original">
                            <?= formatPrice($product['price']); ?>
                        </span>
                        &rarr;
                        <span class="final">
                            <?= formatPrice($final_price); ?>
                        </span>
                    <?php else: ?>
                        <span class="final">
                            <?= formatPrice($product['price']); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</body>
</html>