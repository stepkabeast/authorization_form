<?php
require_once __DIR__.'/boot.php';

$user = null;
$joke = null;

if (check_auth()) {
    // Получим данные пользователя по сохранённому идентификатору
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Запрос к API для получения случайной шутки
    $api_url = 'https://official-joke-api.appspot.com/jokes/random';
    $response = file_get_contents($api_url);
    $joke = json_decode($response, true);

    $ip_response = file_get_contents('https://api.ipify.org?format=json');
    $ip = json_decode($ip_response, true);
    //echo "My public IP address is: " . $ip;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Auth Demo</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/styles.css">
  <style>
   .joke-setup {
    font-size: 1.2em;
    color: #2c3e50;
    margin-bottom: 10px;
    padding-left: 10px;
    border-left: 5px solid #3498db;
}

    .joke-punchline {
       font-size: 1.4em;
       color: #e74c3c;
       padding-left: 10px;
       border-left: 5px solid #e74c3c;
       margin-top: 10px;
       font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row py-5">
    <div class="col-lg-6">

        <?php if ($user) { ?>

          <h1>Welcome back, <?=htmlspecialchars($user['username'])?>!</h1>
          <h2 style="margin-top:50px;">Here is a block for jokes</h2>

          <div class="joke-setup"><?=htmlspecialchars($joke['setup'])?></div>
          <div class="joke-punchline"><?=htmlspecialchars($joke['punchline'])?></div>
          <div class="IP" style="margin-top:50px;"><?=htmlspecialchars("My public IP address is: " . $ip['ip'])?></div>

          <form class="mt-5" method="post" action="do_logout.php">
            <button type="submit" class="btn btn-primary">Logout</button>
          </form>

        <?php } else { ?>

          <h1 class="mb-5">Registration</h1>

            <?php flash(); ?>

          <form method="post" action="do_register.php">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Register</button>
              <a class="btn btn-outline-primary" href="login.php">Login</a>
            </div>
          </form>

        <?php } ?>

    </div>
  </div>
</div>

</body>
</html>
