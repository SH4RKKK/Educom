<?php
$orders = $_SESSION['orders'] ?? [];
$total  = 0;
?>

<div class="cart-wrapper">
  <h1 class="cart-title">Shopping Cart</h1>

  <div class="cart-container">
    <!-- left: items -->
    <div class="cart-items">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $id => $item): 
                $subtotal = $item['price'] * $item['amount'];
                $total += $subtotal;
          ?>
          <tr>
            <td class="cart-product">
              <?php if (!empty($item['image_path'])): ?>
                <img src="images/<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              <?php endif; ?>
              <span><?= htmlspecialchars($item['name']) ?></span>
            </td>
            <td>€<?= number_format($item['price'], 2, ',', '.') ?></td>
            <td><?= (int)$item['amount'] ?></td>
            <td>€<?= number_format($subtotal, 2, ',', '.') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- right: totals -->
    <div class="cart-summary">
      <h3>Cart totals</h3>
      <div class="summary-row">
        <span>Subtotal</span>
        <span>€<?= number_format($total, 2, ',', '.') ?></span>
      </div>
      <div class="summary-row">
        <span>Shipping</span>
        <span>Free</span>
      </div>
      <div class="summary-total">
        <span>Total</span>
        <span>€<?= number_format($total, 2, ',', '.') ?></span>
      </div>
      <form method="post" action="index.php?page=checkout">
        <button type="submit" class="checkout-btn">Proceed to checkout</button>
      </form>
    </div>
  </div>
</div>