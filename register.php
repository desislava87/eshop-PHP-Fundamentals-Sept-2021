<?php
    require_once('config/db.php');
    require_once('config/functions.php');



$name = isset($_POST['name']) ? $_POST['name'] : "";
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$phone = isset($_POST['phone']) ? $_POST['phone'] : "";
$image = isset($_FILES['image']) ? $_FILES['image'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";
$repassword = isset($_POST['repassword']) ? $_POST['repassword'] : "";
$status = isset($_POST['status']) ? $_POST['status'] : "";


if(isset($_POST['register'])) {

    $errors=[];
    $name = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : "";
    $lastname = !empty($_POST['lastname']) ? htmlspecialchars(trim($_POST['lastname'])) : "";
    $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : "";
    $phone = !empty($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : "";
    $image = !empty($_FILES['image']) ? $_FILES['image'] : "";
    $password = !empty($_POST['password']) ? $_POST['password'] : "";
    $repassword = !empty($_POST['repassword']) ? $_POST['repassword'] : "";
    $status = !empty($_POST['status']) ? $_POST['status'] : "";

 

    if(!mb_strlen($name)) {
        $errors['name'] = "Въведете име.";
    } else if(mb_strlen($name) > 32) {
        $errors['name'] = "Максималната дължина на името е 32 символа.";
    }


    if(!mb_strlen($lastname)) {
        $errors['lastname'] = "Въведете фамилия.";
    } else if(mb_strlen($lastname) > 32) {
        $errors['lastname'] = "Максималната дължина на фамилия е 32 символа.";
    }


    if(!mb_strlen($email)) {
        $errors['email'] = "Въведете имейл адрес.";
    } else if(mb_strlen($email) > 64) {
        $errors['email'] = "Максималната дължина на имейл адрес е 64 символа";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Въведете коректен имейл адрес.";
    } else {
        $sql = "SELECT `id`
            FROM `users`
            WHERE `email` LIKE '".$email."'
    ";

        $query=mysqli_query($conn, $sql);

        if(mysqli_num_rows($query)) {
            $errors['email'] = "Съществува потребител с въведения имейл адрес.";
        }
    }


    if(!mb_strlen($phone)) {
        $errors['phone'] = "Въведете телефонен номер.";
    } else if(mb_strlen($phone) < 7 || mb_strlen($phone) > 13) {
        $errors['phone'] = "Телефонният номер трябва да е между 7 и 13 символа.";
    } else if(!is_numeric($phone)) {
        $errors['phone'] = "Въведете коректен телефонен номер.";
    }


    if(!mb_strlen($password)) {
        $errors['password'] = "Въведете парола.";
    } else if(mb_strlen($password) < 8 || mb_strlen($password) > 100) {
        $errors['password'] = "Паролата трябва да е между 8 и 100 символа.";
    }


    if(!mb_strlen($repassword)) {
        $errors['repassword'] = "Повторете парола.";
    } else if($password !== $repassword) {
        $errors['repassword'] = "Въведените пароли не съвпадат.";
    }
    

//довършване на заявката
    if(!count($errors)) {
        $password=password_hash($password,PASSWORD_DEFAULT);
        //не въвежда правилно created_by и updated_by
        $last_id = mysqli_insert_id($conn);
        $sql = "INSERT INTO `users` (
                `type_id`,
                `name`,
                `lastname`,
                `email`,
                `phone`,
                `image`,
                `password`,
                `status`,
                `created_at`,
                `created_by`,
                `updated_at`,
                `updated_by`
                ) VALUES (
                    2,
                    '".mysqli_real_escape_string($conn, $name)."',
                    '".mysqli_real_escape_string($conn, $lastname)."',
                    '".mysqli_real_escape_string($conn, $email)."',
                    '".mysqli_real_escape_string($conn, $phone)."',
                    '".mysqli_real_escape_string($conn, $image)."',
                    '".mysqli_real_escape_string($conn, $password)."',
                    'active',
                    NOW(),
                    '".mysqli_real_escape_string($conn, $last_id)."',
                    NOW(),
                    '".mysqli_real_escape_string($conn, $last_id)."'
                )
        ";

        $query=mysqli_query($conn, $sql);

        if(!$query) {
            $errors['sql'] = "Възникна грешка, свържете се с нас.";
            echo mysqli_error($conn);


        } else {
            $success = "Успешна регистрация!";
        }
        
    }

    // showArray($_FILES['image']);
    showArray($errors);


}





?>


<?php require_once('header.php');?>

        <h2>Регистрационна форма:</h2>
        <form action="" method="POST">
            <div>
                <h4>Име:</h4>
                <input type="text" name="name" placeholder="Въведете име" value="<?=$name?>">
            </div>
            <div>
                <h4>Фамилия:</h4>
                <input type="text" name="lastname" placeholder="Въведете фамилия" value="<?=$lastname?>">
            </div>
            <div>
                <h4>Имейл:</h4>
                <input type="text" name="email" placeholder="Въведете имейл адрес" value="<?=$email?>">
            </div>
            <div>
                <h4>Телефонен номер:</h4>
                <input type="text" name="phone" placeholder="Въведете телефонен номер" value="<?=$phone?>">
            </div>
            <div>
                <h4>Парола:</h4>
                <input type="password" name="password" placeholder="Въведете парола">
            </div>
            <div>
                <h4>Повторете парола:</h4>
                <input type="password" name="repassword" placeholder="Повторете парола">
            </div>
            <br>
            <input type="submit" name="register" value="Регистрация">
            <div>
                <?php if(isset($errors) && count($errors)) :?>
                    <ul>    
                        <?php foreach($errors as $error) :?>
                            <li>
                                <?=$error?>
                            </li>
                        <?php endforeach?>
                    </ul>
                <?php endif?>
            </div>
            <div>
                <?php if(isset($success)) :?>
                    <ul>
                        <li>
                            <?=$success?>
                            <?=header("Location:login.php")?>
                        </li>
                    </ul>
                <?php endif?>
            </div>
        </form>
        <br>
    </body>
</html>               
