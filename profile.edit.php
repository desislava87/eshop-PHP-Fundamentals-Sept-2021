<?php
    require_once('config/db.php');
    session_start();
    require_once('config/functions.php');

    $user_id = $_SESSION['user']['id'];
    $name = isset($_POST['name']) ? $_POST['name'] : $_SESSION['user']['name'];
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : $_SESSION['user']['lastname'];
    $email = isset($_POST['email']) ? $_POST['email'] : $_SESSION['user']['email'];
    $phone = isset($_POST['phone']) ? $_POST['phone'] : $_SESSION['user']['phone'];


    if(isset($_POST['edit'])) {
        $errors = [];
        $name = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : $name;
        $lastname = !empty($_POST['lastname']) ? htmlspecialchars(trim($_POST['lastname'])) : $lastname;
        $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : $email;
        $phone = !empty($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : $phone;


        if(!mb_strlen($name)) {
            $errors['name'] = "Въведете име";
        } else if(mb_strlen($name) > 32) {
            $errors['name'] = "Името трябва да е до 32 символа";
        }

        if(!mb_strlen($lastname)) {
            $errors['lastname'] = "Въведете фамилия";
        } else if(mb_strlen($lastname) > 32) {
            $errors['lastname'] = "Фамилията трябва да е до 32 символа";
        }

        if(!mb_strlen($email)) {
            $errors['email'] = "Въведете имейл адрес";
        } else if(mb_strlen($email) > 64) {
            $errors['email'] = "Имейл адресът трябва да е до 64 символа";
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Имейл адресът е некоректен";
        } else {
            $sql = "SELECT `id`
                    FROM `users`
                    WHERE
                        `email` LIKE '".$email."' AND
                        `id` <> '".$user_id."'
            ";

            $query = mysqli_query($conn, $sql);
            if(mysqli_num_rows($query)) {
                $errors['email'] = "Има регистриран потребител със същия имейл адрес.";
            }
        }

        if(!mb_strlen($phone)) {
            $errors['phone'] = "Въведете телефонен номер.";
        } else if(mb_strlen($phone) < 7 || mb_strlen($phone) > 13) {
            $errors['phone'] = "Телефонният номер трябва да е между 7 и 13 символа.";
        } else if(!is_numeric($phone)) {
            $errors['phone'] = "Въведете коректен телефонен номер.";
        }

        if(!count($errors)) {
            $sql = "UPDATE `users`
                    SET
                        `name` = '".$name."',
                        `lastname` = '".$lastname."',
                        `email` = '".$email."',
                        `phone` = '".$phone."'
                    WHERE `users` . `id` = '".$_SESSION['user']['id']."'
            ";

            $query = mysqli_query($conn, $sql);
            if($query) {
                $_SESSION['user'] = [
                    'id' => $user_id,
                    'name' => $name,
                    'lastname' => $lastname,
                    'email' => $email,
                    'phone' => $phone
                ];

                header("Location: profile.php");
            } else {
                $errors['sql'] = "Възникна грешка, свържете се с администратор";
            }
        }
        showArray($errors);
    }

    showArray($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <h2>Редактиране на профил</h2>
        <form action="" method="POST">
            <div>
                <h4>Промяна име</h4>
                <input type="text" name="name" value="<?=$name?>">
            </div>
            <div>
                <h4>Промяна фамилия</h4>
                <input type="text" name="lastname" value="<?=$lastname?>">
            </div>
            <div>
                <h4>Промяна имейл адрес</h4>
                <input type="text" name="email" value="<?=$email?>">
            </div>
            <div>
                <h4>Промяна на телефонен номер</h4>
                <input type="text" name="phone" value="<?=$phone?>">
            </div>
            <br>
            <input type="submit" name="edit" value="Редактиране">

            <div>
                <?php if (isset($errors) && count($errors)) :?>    
                    <ul>
                        <?php foreach($errors as $error) :?>
                            <li>
                                <?=$error?>
                            </li>
                        <?php endforeach?>
                    </ul>
                <?php endif?>
            </div>
        </form>

        <br>
        <a href="profile.php">Профил</a>
    </body>
</html>