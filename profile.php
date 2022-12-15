<?php
    require_once('config/db.php');

session_start();

if(!isset($_SESSION['user'])) {
    header("Location:login.php");
    exit;
}

?>

<?php require_once('header.php');?>

        <h1>
            <?php echo "Здравей, " . $_SESSION['user']['name'] . " " . $_SESSION['user']['lastname'] . "!";?>
        </h1>
        <div>
                <a href="profile.edit.php">Редактиране на профил</a>
                <br><br>
                <a href="logout.php">Изход</a>
        </div>
    </body>
</html>



