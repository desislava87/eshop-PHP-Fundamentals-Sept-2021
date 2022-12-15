<?php
require_once('config/db.php');
require_once('config/functions.php');


$id=isset($_GET['id']) ? (int)$_GET['id'] : 0;


$sql = "SELECT 
            p. `id`,
            p. `name` AS 'product',
            p. `description`,
            p. `image`,
            p. `price`,
            p. `count`,
            p. `delivery_days`,
            p. `category_id`,
            c. `name` AS 'category'
        FROM `products` p
        LEFT JOIN `categories` c ON
            p.`category_id` = c.`id`
        WHERE
            p.`id` = '".$id."' AND
            p. `status` = 'active' AND
            c. `status` = 'active'
";

$query = mysqli_query($conn,$sql);
if($query && mysqli_num_rows($query)) {
    $result = mysqli_fetch_assoc($query);
} else {
    header('Location:products.php');
}
// showArray($result);

//Други продукти от същата категория

$sql = "SELECT
            `id`,
            `name` AS 'product',
            `image`
        FROM `products`
        WHERE
            `id` <> '".$id."' AND
            `category_id` = '".$result['category_id']."' AND
            `status` = 'active'
        LIMIT 3
";

$query = mysqli_query($conn, $sql);
$products = [];
if($query) {
    while($row = mysqli_fetch_assoc($query)) {
        $products[] = $row;
    }
}

?>

<?php require_once('header.php');?>
    <div class="container">
        <div class="breadcrumb">
            <ul>
                <li>
                    <a href="products.php">Продукти</a> / 
                </li>
                <li>
                    <a href="products.php?cat=<?=$result['category_id']?>"><?=$result['category']?></a> / 
                </li>
                <li>
                    <?=$result['product']?>
                </li>
            </ul>
        </div>
    <div class="container">
        <div class="product-page">
            <h2 class="text-center"><?=$result['product']?></h2>
            <img src="<?=$result['image']?>" alt="">
            <p>
                <?=$result['description']?>
            </p>
            <p>
                Допълнителна информация:
            </p>
            <ul>
                <li>Цена: <?=$result['price']?>лв.</li>
                <li>Бройки: <?=$result['count']?></li>
                <li>Време за доставка: <?=$result['delivery_days']?></li>
            </ul>
        </div>
    </div>
        <hr>
        <?php if($products && count($products)) : ?>
            <div class="products">
                <h2>Други продукти от същата категория</h2>

                <?php for($i = 0; $i < count($products); $i++) :?>
                    <div class="product-item">
                        <h3><?=$products[$i]['product']?></h3>
                        <img src="<?=$products[$i]['image']?>" alt="">
                        <a href="product.php?id=<?=$products[$i]['id']?>">Виж повече</a>
                    </div>
                <?php endfor ?>
            </div>
        <?php endif?>
    </div>

    <?php require_once('footer.php'); ?>

