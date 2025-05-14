<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;

// Predefined categories
$categories = ['Electronics', 'Apparel', 'Home & Living', 'Beauty', 'Sports', 'Automotive', 'Toys', 'Office'];

// Category image map
$categoryImages = [
    'Electronics'   => Yii::app()->baseUrl . '/images/categories/electronics.png',
    'Apparel'       => Yii::app()->baseUrl . '/images/categories/apparel.png',
    'Home & Living' => Yii::app()->baseUrl . '/images/categories/home.png',
    'Beauty'        => Yii::app()->baseUrl . '/images/categories/beauty.png',
    'Sports'        => Yii::app()->baseUrl . '/images/categories/sports.png',
    'Automotive'    => Yii::app()->baseUrl . '/images/categories/automotive.png',
    'Toys'          => Yii::app()->baseUrl . '/images/categories/toys.png',
    'Office'        => Yii::app()->baseUrl . '/images/categories/office.png',
];

// Category filter
$selectedCategory = Yii::app()->request->getQuery('category');

$criteria = new CDbCriteria;
$criteria->limit = 8;
$criteria->order = 'RAND()';
$criteria->addCondition("status = 'active'");

if ($selectedCategory && in_array($selectedCategory, $categories)) {
    $criteria->addCondition('category = :cat');
    $criteria->params[':cat'] = $selectedCategory;
}

$products = Products::model()->findAll($criteria);
?>

<div class="container">

    <!-- HERO Section -->
    <div class="card mb-5 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <img src="<?php echo Yii::app()->baseUrl; ?>/images/hero-main.png" class="img-fluid rounded" alt="Main Banner">
                </div>
                <div class="col-md-4 d-flex flex-column gap-3">
                    <img src="<?php echo Yii::app()->baseUrl; ?>/images/hero-side-1.png" class="img-fluid rounded shadow-sm" alt="Banner 1">
                    <img src="<?php echo Yii::app()->baseUrl; ?>/images/hero-side-2.png" class="img-fluid rounded shadow-sm" alt="Banner 2">
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="card mb-5 shadow-sm">
        <div class="card-body">
            <h4 class="mb-3">Shop by Category</h4>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3">
                <?php foreach ($categories as $category): ?>
                    <?php
                        $isSelected = ($selectedCategory === $category);
                        $categoryImg = CHtml::encode($categoryImages[$category] ?? Yii::app()->baseUrl . '/images/categories/placeholder.png');
                    ?>
                    <div class="col">
                        <a href="<?php echo Yii::app()->createUrl('site/index', ['category' => $category]); ?>"
                           class="text-decoration-none"
                           aria-label="Browse <?php echo CHtml::encode($category); ?>">
                            <div class="card category-tile text-center shadow-sm h-100 border <?php echo $isSelected ? 'border-primary' : ''; ?>">
                                <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center" style="height: 130px;">
                                    <img src="<?php echo $categoryImg; ?>" alt="<?php echo CHtml::encode($category); ?>"
                                         class="mb-2" style="width: 40px; height: 40px; object-fit: contain;">
                                    <div class="text-muted small fw-semibold"><?php echo CHtml::encode($category); ?></div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Daily Discover -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3">
                <?php echo $selectedCategory ? CHtml::encode($selectedCategory) . ' Deals' : 'Daily Discover'; ?>
            </h4>

            <?php if (empty($products)): ?>
                <p class="text-muted">No products found in this category.</p>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 g-3">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <?php if ($product->image_url): ?>
                                    <img src="<?php echo CHtml::encode($product->image_url); ?>"
                                         class="card-img-top"
                                         alt="<?php echo CHtml::encode($product->name); ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo CHtml::encode($product->name); ?></h6>
                                    <p class="card-text text-danger fw-bold mb-2">
                                        ₱<?php echo number_format($product->price, 2); ?>
                                    </p>
                                    <a href="<?php echo Yii::app()->createUrl('products/view', ['id' => $product->id]); ?>"
                                       class="btn btn-sm btn-ksc w-100">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
