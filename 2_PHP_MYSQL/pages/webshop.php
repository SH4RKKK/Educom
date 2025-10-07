<?php 
//Fix this to correcet logic.

$products = [
    ['img' => 'images/1.avif', 'name' => 'Product 1'],
    ['img' => 'images/2.avif', 'name' => 'Product 2']
];
?>
<body>

<div class='page'>
<?php foreach ($products as $p): ?>
  <div class="card">
    <img src="<?php echo htmlspecialchars($p['img'], ENT_QUOTES); ?>"
         alt="<?php echo htmlspecialchars($p['name']); ?>">
    <div class="actions">
      <input type="number" name="quantity" min="1" value="1">
      <button>Order Now</button>
    </div>
  </div>
<?php endforeach; //Order button only when logged or Login to order now?>
</div>