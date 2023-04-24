<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание</title>
</head>
<body>
    <div>
        <p>Древовидная структура:</p>
        <?php include "categories.php" ?>
    </div>
    <form method="POST" action="categories.php">
        <button name="add_one">Добавить 1 случайную категорию</button>
        <button name="add_many">Добавить 5000 случайных категорий</button>
        <button name="delete_many">Удалить всё</button>
    </form>
</body>
</html>