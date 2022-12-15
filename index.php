<?php

    require_once('config/db.php');
    require_once('config/functions.php');


    $id=isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Продукти
    $sql = "SELECT
                p.`id`,
                p.`name` AS 'phone_name',
                p.`description`,
                p.`image`,
                c.`name` AS 'category'
            FROM `products` p
            LEFT JOIN `categories`c
                ON p.`category_id` = c.`id`
            WHERE 
                p. `status` = 'active' AND
                c. `status` = 'active' 
            ORDER BY p.`id` DESC
            LIMIT 4
    ";

    $query = mysqli_query($conn, $sql);
    if($query) {
        while($row = mysqli_fetch_assoc($query)) {
            $row['description'] = mb_substr($row['description'], 0, 100) . "...";
            $info[] = $row;
        }
    }


    //Новини

    $sql = "SELECT
                `id`,
                `title`,
                `description`,
                `image`
            FROM `news`
            WHERE 
                `id` != '".$id."'
            ORDER BY `id` DESC
            LIMIT 3
    ";

    $query = mysqli_query($conn, $sql);
    $news = [];

    if($query) {
        while($row = mysqli_fetch_assoc($query)) {
            $row['description'] = mb_substr($row['description'], 0, 100) . "...";
            $news[] = $row;
        }
    }


    // showArray($news);
    // showArray($info);

?>

    <?php require_once('header.php'); ?>
        <!-- Site info -->
        <div class="content">
            <h1>Добре дошли в Онлайн магазин HAILEY !!!</h1>
            <p>
                Онлайн магазин "HAILEY" е един от водещите търговци в България в продажбата на компютри, телефони, аксесоари и допълнителни компоненти.
            </p>
            <p>
                Вече повече от 25 г. нашата компания се стреми да предостави на клиентите широка гама висококачествена техника на атрактивни цени, подкрепено от професионално обслужване, съвети, осигурен гаранционен сервиз в оторизирани сервизни бази в цялата страна, изгодни схеми на лизинг и др.
            </p>
            <img src="assets/img/content.png" alt="">
        </div>

        <!-- Products -->
        <div class="products text-center">
            <h2>Нашите продукти</h2>
            <?php for($i = 0; $i < count($info); $i++) :?>
                <div class="product-item">
                    <h3><?=$info[$i]['phone_name']?></h3>
                    <p>
                        <?=$info[$i]['description']?>
                    </p>
                    <img src="<?=$info[$i]['image']?>" alt="">
                    <a href="product.php?id=<?=$info[$i]['id']?>">Виж повече</a>
                </div>
            <?php endfor ?>
        </div>

        <!-- Subscribe -->
        <div class="subscribe text-center">
            <h2>Абонирай се за най-актуалните промоции и новини</h2>
            <form action="" method="POST">
                <input type="text" name="email" placeholder="Въведи имейл адрес">
                <input type="submit" name="subscribe" value="Абониране">
            </form>
        </div>

        <!-- News -->
        <div class="products news">
            <h2>Последни новини</h2>
            <?php for($i = 0; $i < count($news); $i++) :?>
                <div class="product-item">
                    <h3><?=$news[$i]['title']?></h3>
                    <p>
                        <?=$news[$i]['description']?>
                    </p>
                    <img src="<?=$news[$i]['image']?>" alt="">
                    <a href="news.php?id=<?=$news[$i]['id']?>">Виж повече</a>
                </div>
            <?php endfor ?>
            </div>
        </div>
        <?php require_once('footer.php'); ?>
