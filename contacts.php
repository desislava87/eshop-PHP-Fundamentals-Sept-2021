<?php

require_once('config/db.php');
require_once('config/functions.php');

//Създаване на променливи

$name = isset($_POST['name']) ? $_POST['name'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$phone= isset($_POST['phone']) ? $_POST['phone'] : "";
$message= isset($_POST['message']) ? $_POST['message'] : "";

if(isset($_POST['submit'])) {
    $errors = [];
    $name = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : "";
    $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : "";
    $phone= !empty($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : "";
    $message= !empty($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : "";
    
    //Валидиране на полета

    if(!mb_strlen($name)) {
        $errors['name'] = "Въведете име.";
    } else if (mb_strlen($name) > 100) {
        $errors['name'] = "Максималната дължина на името е 100 символа.";
    }

    if(!mb_strlen($email)) {
        $errors['email'] = "Въведете имейл адрес.";
    }
     else if (mb_strlen($email) > 100) {
        $errors['email'] = "Максималната дължина на имейл адресът е 100 символа.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Некоректен имейл адрес.";
    }

    if(!mb_strlen($phone)) {
        $errors['phone'] = "Въведете телефонен номер.";
    } 
    else if (mb_strlen($phone) > 13 || mb_strlen($phone) < 8) {
        $errors['phone'] = "Дължината на телефонния номер е между 8 и 13 символа.";
    } else if(!is_numeric($phone)) {
        $errors['phone'] = "Некоректен телефонен номер.";
    }

    if(!mb_strlen($message)) {
        $errors['message'] = "Въведете съобщение.";
    }

    // showArray($errors);


    if(!count($errors)) {
        $sql = "INSERT INTO `messages`(
            `name`,
            `email`,
            `phone`,
            `message`,
            `date`,
            `is_read`
        ) VALUES (
            '".mysqli_real_escape_string($conn, $name)."',
            '".mysqli_real_escape_string($conn, $email)."',
            '".mysqli_real_escape_string($conn, $phone)."',
            '".mysqli_real_escape_string($conn, $message)."',
            NOW(),
            0
        )";

        $query = mysqli_query($conn, $sql);
        if($query) {
            $success = "Успешно изпратено съобщение. Очаквайте да се свържем с Вас.";
            // header('Location: index.php');
        } else {
            $errors['sql'] = "Грешка";
        }
    }
}
?>

<?php require_once('header.php');?>


<div class="contacts">
    <h2 class="text-center">При възникнали въпроси се свържете с нас:</h2>
    <form action="" method="POST">
        <div>
            <h3>Име:</h3>
            <input type="text" name="name" placeholder="Въведете име" value="<?=$name?>">
        </div>
        <div>
            <h3>Имейл адрес:</h3>
            <input type="text" name="email" placeholder="Въведете имейл адрес" value="<?=$email?>">
        </div>
        <div>
            <h3>Телефонен номер:</h3>
            <input type="text" name="phone" placeholder="Въведете телефонен номер" value="<?=$phone?>">
        </div>
        <div>
            <h3>Съобщение:</h3>
            <textarea name="message" placeholder="Въведете съобщение"><?=$message?></textarea>
        </div>
        <br>
        <div>
            <input type="submit" name="submit" value="Изпрати">
        </div>
        <br>
    </form>
    <div>
        <?php if(isset($errors) && count($errors)) :?>
            <ul>
                <?php foreach($errors as $error) :?>
                    <li><?=$error?></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </div>
    <div>
        <?php if(isset($success)) :?>
            <p>
                <?=$success?>
                <?=header("Location:index.php")?>
            </p>
        <?php endif ?>
    </div>
</div>


<?php require_once('footer.php');?>