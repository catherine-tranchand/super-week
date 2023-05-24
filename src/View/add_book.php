<?php
?>
<?php

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add a book</title>
</head>
<body>
<h1>Add a book</h1>
<div class="error"></div>
<form id="booksForm" method="POST">
    <label for="title">Titre</label>
    <input name="title" type="text">
    <br>
    <label for="content">Texte</label>
    <textarea name="content"></textarea>
    <br>
    <button type="submit">Add a book</button>
</form>
</body>
</html>