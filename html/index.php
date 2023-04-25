<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Тестовое задание</title>
</head>
<body>
    <div id="categories">
        <p id="title">Древовидная структура:</p>
        <?php include "categories_form.php" ?>
    </div>
    <form method="POST" action="categories_form.php">
        <button class="btn btn-primary" name="add_one">Добавить 1 случайную категорию</button>
        <button class="btn btn-primary" name="add_many">Добавить 5000 случайных категорий</button>
        <button class="btn btn-danger" name="delete_many">Удалить всё</button>
    </form>
</body>
</html>