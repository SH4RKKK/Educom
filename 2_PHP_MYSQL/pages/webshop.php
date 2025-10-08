<?php 
$currentPage = $_SESSION['webshoppage'] ?? 1;

if (empty($response['message'])) {
    showProducts(4,$currentPage,$response['items']);
} else {
    showMessage($response['message']);
}

?>

<!-- Pagination buttons -->
<form method="post" style="text-align:center; margin-top:20px;" action="index.php">
<input type="hidden" name="page" value="webshop">
  <?php for ($i = 1; $i <= 2; $i++): ?>
    <button type="submit" name="webshoppage" value="<?= $i ?>"
      <?= $i === $currentPage ? 'disabled style="font-weight:bold;"' : '' ?>>
      <?= $i ?>
    </button>
  <?php endfor; closeDiv();?>
</form>