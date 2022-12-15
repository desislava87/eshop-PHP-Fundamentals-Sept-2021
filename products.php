<?php
require_once('config/db.php');
require_once('config/functions.php');

$category = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;

if($category) {
    $sql = " SELECT `name`
            FROM `categories`
            WHERE
            `id` = '".$category."' AND
            `status` = 'active'
            LIMIT 1
    ";
    $query = mysqli_query($conn, $sql);
    if($query && mysqli_num_rows($query)) {
        $result = mysqli_fetch_assoc($query);
    } else {
        header('Location: products.php');
    }
}

//Продукти
$sql = "SELECT
        p.`id`,
        p.`name` AS 'product',
        p.`description`,
        p.`image`,
        c.`name` AS 'category'
        FROM `products` p
        LEFT JOIN `categories`c
        ON p.`category_id` = c.`id`
        WHERE
            p. `status` = 'active' AND
            c. `status` = 'active'
        ";

        if($category) {
            $sql .= "AND p.`category_id` = '".$category."'";
        }

        $sql .= "ORDER BY p. `updated_at` DESC";

        $query = mysqli_query($conn, $sql);
        if($query) {
            while($row = mysqli_fetch_assoc($query)) {
                $row['description'] = mb_substr($row['description'], 0, 100) . "...";
                $products[] = $row;
            }
        }

// showArray($products);

?>


<?php require_once('header.php');?>

    <div class="products-page">
        <div class="products">
            <?php if($category) :?>
                <h2><?=$result['name']?></h2>
            <?php endif?>
            <h1 class="text-center">Продукти</h1>
            <?php for($i = 0; $i < count($products); $i++) :?>
                <div class="product-item">
                    <h3><?=$products[$i]['product']?></h3>
                    <p>
                        <?=$products[$i]['description']?>
                    </p>
                    <img src="<?=$products[$i]['image']?>" alt="<?=$products[$i]['product']?>">
                    <a href="product.php?id=<?=$products[$i]['id']?>">Разгледай</a>
                </div>
            <?php endfor ?>
        </div>
    </div>

    <?php require_once('footer.php'); ?>