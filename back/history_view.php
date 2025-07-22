<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История просмотров</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: rgb(255, 245, 245); /* Бежевый фон */
        margin: 0;
        padding: 0;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #444;
    }

    #viewed-stations {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
    }

    .station {
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
    }

    .station:last-child {
        border-bottom: none;
    }

    .station:hover {
        background-color: #f0f8ff;
    }

    button {
        background-color: #007BFF; /* Синий цвет */
        color: #fff; /* Белый текст */
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        margin-top: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    button:hover {
        background-color: #0056b3; /* Более тёмный синий при наведении */
        transform: scale(1.05); /* Лёгкое увеличение кнопки */
    }

    button:active {
        background-color: #004080; /* Ещё темнее при нажатии */
        transform: scale(1); /* Возвращаем в нормальный размер */
    }
</style>
</head>
<body>
<button onclick="window.location.href='dashboard_view.php'">Главная страница</button>
    <h1>История просмотров станций</h1>
    <div id="viewed-stations">
        <!-- Здесь будет отображаться история -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        loadViewedStations();
    });

    function loadViewedStations() {
        fetch('history.php', {
            method: 'POST',
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('viewed-stations');
            if (data.success && data.viewedStations.length > 0) {
                container.innerHTML = ''; // Очищаем контейнер
                data.viewedStations.forEach(station => {
                    const stationElement = document.createElement('div');
                    stationElement.classList.add('station');
                    stationElement.textContent = `${station.name}, ${station.city}, ${station.street}, ${station.house}`;
                    container.appendChild(stationElement);
                });
            } else {
                container.innerHTML = '<p>История просмотров пуста</p>';
            }
        })
        .catch(error => {
            const container = document.getElementById('viewed-stations');
            container.innerHTML = `<p>Ошибка запроса: ${error.message}</p>`;
            console.error('Ошибка:', error);
        });
    }

    </script>
</body>
</html>
