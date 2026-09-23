<?php
// Assuming you already fetched plants from DB as $plant
echo "
    <div class='plant-card'>
        <img src='uploads/{$plant['image']}' alt='{$plant['name']}' />
        <h3>{$plant['name']}</h3>
        <p>{$plant['description']}</p>
        <p>Price: ₹{$plant['price']}</p>
        <form method='POST' action='add_to_cart.php'>
            <input type='hidden' name='plant_id' value='{$plant['id']}'>
            <input type='number' name='quantity' value='1' min='1'>
            <button type='submit'>Add to Cart</button>
        </form>
    </div>
";
?>
