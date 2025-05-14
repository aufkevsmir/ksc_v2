<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Shared KSC Styles -->
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/shared.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
        }
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4 shadow-sm" style="background-color: #f84f30;">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="<?php echo Yii::app()->homeUrl; ?>">
                <i class="bi bi-shop-window"></i> KSC B2B
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('site/index'); ?>"><i class="bi bi-house-door"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('products/index'); ?>"><i class="bi bi-box-seam"></i> Products</a></li>

                    <?php if (!Yii::app()->user->isGuest): ?>
                        <?php if (Yii::app()->user->getState('role') === 'buyer'): ?>
                            <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('cart/index'); ?>"><i class="bi bi-cart"></i> My Cart</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('orders/my'); ?>"><i class="bi bi-bag-check"></i> My Orders</a></li>
                        <?php elseif (in_array(Yii::app()->user->getState('role'), ['seller', 'admin'])): ?>
                            <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('products/manage'); ?>"><i class="bi bi-box"></i> Manage Products</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('orders/received'); ?>"><i class="bi bi-clipboard-check"></i> Orders Received</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('products/dashboard'); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (Yii::app()->user->isGuest): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('site/login'); ?>"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('site/register'); ?>"><i class="bi bi-person-plus"></i> Register</a></li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-white disabled" tabindex="-1"><i class="bi bi-person-circle"></i> Hi, <?php echo CHtml::encode(Yii::app()->user->getState('full_name')); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo Yii::app()->createUrl('site/logout'); ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success text-center mb-0 rounded-0">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-danger text-center mb-0 rounded-0">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Breadcrumbs -->
    <?php if (isset($this->breadcrumbs)): ?>
        <div class="container mb-3">
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links' => $this->breadcrumbs,
            )); ?>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container main-content">
        <?php echo $content; ?>
    </div>

    <!-- Optional Footer -->
    <?php if (!isset($this->noFooter) || !$this->noFooter): ?>
        <?php $this->renderPartial('//layouts/_footer'); ?>
    <?php endif; ?>

</div> <!-- /.page-wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
