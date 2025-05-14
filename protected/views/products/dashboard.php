<?php
$this->breadcrumbs = ['Seller Dashboard'];
?>

<div class="card shadow-sm mb-5">
    <div class="card-body">
        <h4 class="mb-2">Seller Dashboard</h4>
        <p class="text-muted mb-4">Quick overview of your store's performance and activity.</p>

        <div class="row row-cols-1 row-cols-md-2 g-4">

            <div class="col">
                <div class="card p-3 shadow-sm border-start border-4 border-primary">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-box-seam fs-2 text-primary"></i>
                        <div>
                            <h6 class="mb-0 text-muted">Active Products</h6>
                            <p class="display-6 mb-0"><?php echo $productCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card p-3 shadow-sm border-start border-4 border-warning">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-receipt fs-2 text-warning"></i>
                        <div>
                            <h6 class="mb-0 text-muted">Orders Received</h6>
                            <p class="display-6 mb-0"><?php echo $orderCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card p-3 shadow-sm border-start border-4 border-success">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bag-check fs-2 text-success"></i>
                        <div>
                            <h6 class="mb-0 text-muted">Items Sold</h6>
                            <p class="display-6 mb-0"><?php echo $itemCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card p-3 shadow-sm border-start border-4 border-danger">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-currency-peso fs-2 text-danger"></i>
                        <div>
                            <h6 class="mb-0 text-muted">Sales Revenue</h6>
                            <p class="display-6 mb-0">₱<?php echo number_format($revenue, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
