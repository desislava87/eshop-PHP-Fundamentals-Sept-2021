<?php
require_once('config/db.php');
require_once('config/functions.php');

//Новини
$news = isset($_GET['news']) ? (int) $_GET['news'] : 0;

$sql = "SELECT
            `id`,
            `title`,
            `description`,
            `image`
        FROM `news`
        WHERE
            `status` = 'active'
        ORDER BY `updated_at` DESC
        ";
           
           
        $query = mysqli_query($conn, $sql);
        if($query) {
            $news = [];
            while($row = mysqli_fetch_assoc($query)) {
                $row['description'] = mb_substr($row['description'], 0, 100) . "...";
                $news[] = $row;
            }
        } else {
            echo mysqli_error($conn);
        }

// showArray($news);


?>


<?php require_once('header.php');?>
    <div class="products-page">
        <div class="products">
            <h1 class="text-center">Новини</h1>
            <?php for($i = 0; $i < count($news); $i++) :?>
                <div class="product-item">
                    <h3><?=$news[$i]['title']?></h3>
                    <p>
                        <?=$news[$i]['description']?>
                    </p>
                    <img src="<?=$news[$i]['image']?>" alt="<?=$news[$i]['news']?>">
                    <a href="one.news.php?id=<?=$news[$i]['id']?>">Разгледай</a>
                </div>
            <?php endfor ?>
        </div>
    </div>

    <?php require_once('footer.php'); ?>