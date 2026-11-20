<?php
// URL: index.php?controller=logon
// Geen sessie: loginformulier. Submit checkt de database met passwordHash.
// Wel sessie: logoff-formulier post naar ?controller=logon&destroy=1.
$databaseConfig = require 'app/config/database.php';
$email = '';
if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
}
$password = '';
if (isset($_POST['password'])) {
    $password = $_POST['password'];
}
$destroy = '';
if (isset($_GET['destroy'])) {
    $destroy = $_GET['destroy'];
}

$message = '';
$showLoginForm = false;
$showLogoffForm = false;
$title = 'Inloggen';

if (empty($_SESSION['account'])) {
    $showLoginForm = true;

    if (!empty($_POST)) {
        if ($email === '' || $password === '') {
            $message = 'Vul email en password in.';
        } else {
            $connection = mysqli_connect(
                $databaseConfig['host'],
                $databaseConfig['username'],
                $databaseConfig['password'],
                $databaseConfig['database'],
                $databaseConfig['port']
            );
            $emailSql = mysqli_real_escape_string($connection, $email);
            $passwordHash = sha1($password);
            $sql = "SELECT `name`, `email` FROM `users` WHERE `email` = '" . $emailSql . "' AND `password` = '" . $passwordHash . "' AND `blocked_at` IS NULL AND `deleted_at` IS NULL LIMIT 1";
            $result = mysqli_query($connection, $sql);

            if ($result === false) {
                $message = 'Inloggen is niet gelukt.';
            } else {
                $user = mysqli_fetch_assoc($result);
                mysqli_free_result($result);

                if ($user === null) {
                    $message = 'Onjuist email of password.';
                } else {
                    $_SESSION['account'] = [
                        'name' => $user['name'],
                        'email' => $user['email'],
                    ];
                    $message = 'Ingelogd als ' . $user['name'] . '.';
                    $showLoginForm = false;
                }
            }
        }
    }
} else {
    $title = 'Uitloggen';

    if ($destroy !== '' && !empty($_POST)) {
        unset($_SESSION['account']);
        $message = 'Je bent uitgelogd.';
    } else {
        $showLogoffForm = true;
    }
}

$messageHtml = '';
if ($message !== '') {
    $messageHtml = '<p class="message">' . escape($message) . '</p>';
}

?>
<h2><?= escape($title) ?></h2>
<?= $messageHtml ?>
<?php if ($showLoginForm): ?>
<form method="post" action="?controller=logon">
    <label for="email">email</label>
    <input id="email" name="email" value="<?= escape($email) ?>">
    <label for="password">password</label>
    <input id="password" name="password" type="password">
    <button type="submit">Login</button>
</form>
<?php endif; ?>
<?php if ($showLogoffForm): ?>
<form method="post" action="?controller=logon&amp;destroy=1">
    <button type="submit" name="logoff" value="1">Logoff</button>
</form>
<?php endif; ?>
