
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сессия завершена</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 20px;
            border-radius: 5px;
            font-size: 18px;
            display: inline-block;
        }
        .btn {
            background-color: #721c24;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>

    <div class="message">
        Ваша сессия истекла. Пожалуйста, войдите снова.
    </div>

    <br>
    <a href="http://localhost/application/view/autorization.html" class="btn">Перейти на страницу входа</a>

</body>
</html>
