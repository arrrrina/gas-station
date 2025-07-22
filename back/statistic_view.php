<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика фирм</title>
    <style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 9999;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Красная полоска сверху */
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
         margin-right: 10px; /* Отступ справа между кнопками */
    }

    .modal-content {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f8f8; /* Бежевый фон */
        padding: 20px;
        border-radius: 8px;
        width: 700px;
        max-width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
        margin-top: 70px; /* Отступ для отображения контента ниже полоски */
    }

    .modal-header {
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: bold;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .modal-body {
        font-size: 14px;
        max-height: 400px;
        overflow-y: auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Вертикальная прокрутка */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* Фиксированная ширина столбцов */
    }

    th, td {
        border: 1px solid #ccc;
        padding: 8px 10px;
        text-align: left;
        font-size: 14px; /* Уменьшение шрифта */
        word-wrap: break-word; /* Перенос длинных слов */
        overflow: hidden;
        white-space: nowrap;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    button {
        padding: 10px 15px;
        font-size: 16px;
        background-color: white; /* Бежевый фон кнопки */
        color: rgb(8, 4, 4); /* Красный цвет текста на кнопке */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    button:hover {
        background: rgb(0, 0, 0); 
        color: white; /* Немного темнее бежевый при наведении */
    }

    .close-btn {
        background-color: red;
        color: black; /* Черные буквы на кнопке */
        padding: 10px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }
</style>

 
</head>
<body>
<div class="top-bar">
        <div class="buttons">
            <button onclick="window.location.href='dashboard_view.php'">Главная страница</button>
            <button onclick="showFirmStatistics()">Фирмы</button>
            <button onclick="showClientStatistics()">Клиенты</button>
            <button onclick="showStationsModal()">Автозаправочные станции</button>
        </div>
    </div>
    <!-- Кнопка для открытия статистики фирм -->
    
    <!-- Модальное окно для станций -->
    <div id="stationsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Статистика автозаправочных станций</h2>
            </div>
            <div class="modal-body">
                <!-- Выпадающий список фирм -->
                <label for="firmSelect">Выберите фирму:</label>
                <select id="firmSelect" onchange="fetchStationStatistics()">
                    <option value="">Выберите фирму</option>
                </select>
                <div id="station-stats">
                    <!-- Данные о станциях будут загружаться сюда -->
                </div>
            </div>
            <button class="close-btn" onclick="closeStationsModal()">Закрыть</button>
        </div>
    </div>
    <!-- Модальное окно -->
    <div id="firmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Статистика фирм и их продаж</h2>
            </div>
            <div class="modal-body" id="modal-body">
                
            </div>
            <button class="close-btn" onclick="closeModal()">Закрыть</button>
        </div>
    </div>
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Статистика клиентов</h2>
            </div>
            <div class="modal-body" id="client-modal-body">
                <!-- Данные о клиентах будут загружаться сюда -->
            </div>
            <button class="close-btn" onclick="closeClientModal()">Закрыть</button>
        </div>
    </div>

    <script>
        // Открытие модального окна
        function showFirmStatistics() {
            // Отправка запроса на сервер
            fetch('get_firms.php')
                .then(response => response.json())
                .then(data => {
                    let modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = ''; // Очистить содержимое перед добавлением новых данных
                    
                    // Проверка, есть ли данные
                    if (data.length > 0) {
                        let table = '<table style="width:100%; border-collapse: collapse; text-align: left;">';
                        table += '<tr><th>Фирма</th><th>Продажи</th><th>Общая сумма</th></tr>';
                        
                        // Перебор данных и создание таблицы
                        data.forEach(firm => {
                            table += `<tr><td>${firm.firm_name}</td><td>${firm.total_sales}</td><td>${firm.price}</td></tr>`;
                        });
                        
                        table += '</table>';
                        modalBody.innerHTML = table; // Вставка таблицы в модальное окно
                    } else {
                        modalBody.innerHTML = '<p>Нет данных для отображения.</p>';
                    }

                    // Открытие модального окна
                    document.getElementById('firmModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Ошибка при получении данных:', error);
                    document.getElementById('modal-body').innerHTML = '<p>Ошибка при загрузке данных.</p>';
                });
        }
        function showClientStatistics() {
            // Отправка запроса на сервер для получения данных о клиентах
            fetch('get_clients.php')
                .then(response => response.json())
                .then(data => {
                    let modalBody = document.getElementById('client-modal-body');
                    modalBody.innerHTML = ''; // Очистить содержимое перед добавлением новых данных
                    
                    if (data.length > 0) {
                        let table = '<table style="width:100%; border-collapse: collapse; text-align: left;">';
                        table += '<tr><th>Клиент</th><th>Покупки</th><th>Общая цена</th></tr>';
                        
                        // Перебор данных о клиентах
                        data.forEach(client => {
                            table += `<tr><td>${client.email}</td><td>${client.purchase_count}</td><td>${client.price}</td></tr>`;
                        });
                        
                        table += '</table>';
                        modalBody.innerHTML = table;
                    } else {
                        modalBody.innerHTML = '<p>Нет данных для отображения.</p>';
                    }

                    // Открытие модального окна
                    document.getElementById('clientModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Ошибка при получении данных:', error);
                    document.getElementById('client-modal-body').innerHTML = '<p>Ошибка при загрузке данных.</p>';
                });
        }
        function showStationsModal() {
            document.getElementById('stationsModal').style.display = 'flex';
            fetch('get_firms.php') // Предполагается, что этот файл уже существует и возвращает список фирм
                .then(response => response.json())
                .then(data => {
                    const firmSelect = document.getElementById('firmSelect');
                    firmSelect.innerHTML = '<option value="" disabled selected hidden>Выберите фирму</option>';
                    data.forEach(firm => {
                        const option = document.createElement('option');
                        option.value = firm.firm_id;
                        option.textContent = firm.firm_name;
                        firmSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Ошибка при загрузке списка фирм:', error);
                });
        }
        function fetchStationStatistics() {
            const firmId = document.getElementById('firmSelect').value;

            if (firmId) {
                fetch(`get_stations.php?firm_id=${firmId}`)
                    .then(response => response.json())
                    .then(data => {
                        const statsContainer = document.getElementById('station-stats');
                        statsContainer.innerHTML = '';
                        if (data.length > 0) {
                            let table = '<table style="width:100%; border-collapse: collapse; text-align: left;">';
                            table += '<tr><th>Станция</th><th>Адрес</th><th>Прибыль</th><th>Клиенты</th></tr>';
                            data.forEach(station => {
                                table += `
                                    <tr>
                                        <td>${station.name}</td>
                                        <td>${station.city}, ул ${station.street} ${station.house}</td>
                                        <td>${station.price} руб</td>
                                        <td>${station.clients}</td>
                                    </tr>`;
                            });
                            table += '</table>';
                            statsContainer.innerHTML = table;
                        } else {
                            statsContainer.innerHTML = '<p>Нет данных для отображения.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при получении данных о станциях:', error);
                        document.getElementById('station-stats').innerHTML = '<p>Ошибка при загрузке данных.</p>';
                    });
            }
        }

        function closeStationsModal() {
            document.getElementById('stationsModal').style.display = 'none';
        }

        // Закрытие модального окна для статистики фирм
        function closeModal() {
            document.getElementById('firmModal').style.display = 'none';
        }

        // Закрытие модального окна для статистики клиентов
        function closeClientModal() {
            document.getElementById('clientModal').style.display = 'none';
        }
    </script>
</body>
</html>