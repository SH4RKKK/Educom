<?php 
function webshop(array $response,int $productPerPage, int $currentPage): void {
    $totalPages = ceil(count($response['items']) / $productPerPage);
    $displayPage = max(1, min($currentPage, $totalPages));
    $startIndex  = ($displayPage - 1) * $productPerPage;

    $productsToShow = array_slice($response['items'], $startIndex, $productPerPage);

    if (empty($response['message'])) {
        $response['items'] ? showProducts($productsToShow) : showMessage('Geen producten te koop');
    } else {
        showMessage($response['message']);
    }

    if($totalPages > 1) {
        showPagination($currentPage, $totalPages);
    }
}
?>