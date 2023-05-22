<?php
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="scripts/login.js"></script>
    <title>Connexion</title>
</head>
<body>
<h1>Mon formulaire de connexion</h1>

<div class="error"></div>
<form id="connectionForm" method="POST">
    <label for='email'>email</label>
    <input name='email' type='email'>
    <br>
    <label for='password'>Password</label>
    <input name='password' type='password'>
    <br>

    <button type="submit">Submit</button>
</form>
</body>
</html>