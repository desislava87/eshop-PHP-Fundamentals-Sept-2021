<?php
    require_once('config/db.php');
    require_once('config/functions.php');


$email = isset($_POST['email']) ? $_POST['email'] : "";


if(isset($_POST['login'])){

    $errors=[];
    $email = !empty($_POST['email']) ? $_POST['email'] : "";
    $password = !empty($_POST['password']) ? $_POST['password'] : "";

    if(!mb_strlen($email)) {
        $errors['email'] = "Въведете имейл адрес.";
    } else {
        $sql = "SELECT 
                `id`,
                `name`,
                `lastname`,
                `email`,
                `phone`,
                `image`,
                `password`
                FROM `users`
                WHERE `email` LIKE '".$email."'        
        ";

        $query=mysqli_query($conn, $sql);
        if(mysqli_num_rows($query)) {
            $user = mysqli_fetch_assoc($query);
        } else {
            $errors['email'] = "Няма потребител с този имейл адрес.";
        }
    }


    if(!mb_strlen($password)) {
        $errors['password'] = "Въведете парола.";
    } 

    if(!count($errors)) {
        if(password_verify($password, $user['password'])) {
            $success = "Успешно влизане в системата";

            session_start();
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'lastname' => $user['lastname'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'image' => $user['image'],
                'password' => $user['password']
            ];

            header('Location: profile.php');

        } else {
            $errors['password'] = "Не съществува такъв потребител";
        }
    }

}

if(isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

?>


<?php require_once('header.php');?>

        <h2>Вход в системата</h2>
        <form action="" method="POST">
            <div>
                <input type="text" name="email" placeholder="Въведете имейл адрес" value="<?=$email?>">
            </div>
            <br>
            <div>
                <input type="password" name="password" placeholder="Въведете парола">
            </div>
            <br>
            <div>
                <input type="submit" name="login" value="Вход">
            </div>
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
                        <li><?=$success?></li>
                    </ul>
                <?php endif?>
            </div>
        </form>

