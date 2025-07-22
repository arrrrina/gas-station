<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background:rgb(255, 245, 245);
        margin: 5;
        padding: 0;
    }
    .top-bar {
        background-color: #940000;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0px 10px;
        height: 70px; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .top-bar .title {
        font-size: 20px;
        font-weight: bold;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .top-bar .buttons {
        display: flex; /* Добавляем свойство flex для размещения кнопок по горизонтали */
    }

    .top-bar .buttons button {
        background-color: white;
        color: #000000;
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px; /* Расстояние между кнопками */
        transition: background-color 0.3s, color 0.3s;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .top-bar .buttons button:hover {
        background-color: #000000;
        color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
        
    .profile-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    h1 {
        text-align: center;
        color: #333;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .profile-info {
        margin-bottom: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .profile-info label {
        font-weight: bold;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    #email {
        font-size: 18px;
        color: #555;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    h2 {
        text-align: left;
        margin-top: 25px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    #purchase-history {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    #purchase-history th, #purchase-history td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    #purchase-history th {
        background-color: #f8f8f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    button {
        display: block;
        width: 200px;
        padding: 10px;
        margin: 30px auto;
        font-size: 16px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    button:hover {
        background-color: #0056b3;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    </style>
    
</head>
<body>
    <div class="top-bar">
        <div class="title">Автозаправочные станции</div>
        <div class="buttons">
            <button onclick="window.location.href='dashboard_view.php'">Главная страница</button>
            <button onclick="logout()">Выйти</button>
        </div>
    </div>
    <div class="profile-container">
        <h1>Личный кабинет</h1>

        <!-- Раздел с email пользователя -->
        <div class="profile-info">
            <label for="email">Email:</label>
            <span id="email"></span> <!-- Здесь будет отображен email -->
        </div>
        
        <!-- История покупок -->
        <h2>История покупок</h2>
        <table id="purchase-history">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Топливо</th>
                    <th>Фирма</th>
                    <th>Количество</th>
                    <th>Цена</th>
                </tr>
            </thead>
            <tbody id="purchase-body">
                <!-- Здесь будут отображаться покупки -->
            </tbody>
        </table>

        
    </div>

    <script>
        // Функция выхода
        function logout() {
            window.location.href = 'logout.php'; // Перенаправляем на страницу выхода
        }

        // Получаем данные о пользователе через PHP (fetch)
        fetch('profile.php')
            .then(response => response.json())
            .then(data => {
                if (data.email) {
                    // Заполняем email
                    document.getElementById('email').textContent = data.email;
                }
                if (data.purchases && data.purchases.length > 0) {
                    const purchaseBody = document.getElementById('purchase-body');
                    data.purchases.forEach(purchase => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${purchase.date}</td>
                            <td>${purchase.fuel_type}</td>
                            <td>${purchase.firm}</td>
                            <td>${purchase.number_of_liter} литров</td>
                            <td>${purchase.price} руб</td>
                        `;
                        purchaseBody.appendChild(row);
                    });
                } else {
                    document.getElementById('purchase-body').innerHTML = "<tr><td colspan='4'>Вы еще не совершали покупок.</td></tr>";
                }
            })
            .catch(error => console.error('Ошибка:', error));
    </script>
</body>
</html>
