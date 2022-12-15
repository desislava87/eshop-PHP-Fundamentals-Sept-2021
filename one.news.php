<?php
require_once('config/db.php');
require_once('config/functions.php');


$id=isset($_GET['id']) ? (int)$_GET['id'] : 0;


$sql = "SELECT
            `id`,
            `title`,
            `description`,
            `image`
        FROM `news`
        WHERE
            `id` = '".$id."' AND
            `status` = 'active'
";


$query = mysqli_query($conn,$sql);
if($query && mysqli_num_rows($query)) {
    $news = mysqli_fetch_assoc($query);
} else {
    header('Location:products.php');
}
// showArray($news);


?>

<?php require_once('header.php');?>
    <div class="container">
        <div class="breadcrumb">
            <ul>
                <li>
                    <a href="news.php">Новини</a> / 
                </li>
                <li>
                    <?=$news['title']?>
                </li>
            </ul>
        </div>
    <div class="container">
        <div class="product-page">
            <h2 class="text-center"><?=$news['title']?></h2>
            <img src="<?=$news['image']?>" alt="">
            <p>
                <?=$news['description']?>
            </p>
        </div>


    <?php require_once('footer.php'); ?>

