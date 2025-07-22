<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:rgb(255, 245, 245); /* Бежевый фон */
        }
        #payment-form {
            background: rgb(255, 255, 255);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
        }
        button {
            background-color: rgb(25, 166, 254);
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: rgb(25, 166, 254);
        }
    </style>
</head>
<body>
    <div id="payment-form">
        <h1>Введите данные карты</h1>
        <input type="text" id="card-input" maxlength="8" placeholder="XXXX XXXX">
        <button id="continue-button">Продолжить</button>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const fuelId = urlParams.get('fuelId');
        const liters = urlParams.get('numberOfLiters');
        const columnId = urlParams.get('columnId');
        const totalPrice = urlParams.get('totalPrice');
        const phpTime = "<?php echo $_SESSION['time']; ?>"; // Время из сессии
        const newTime = new Date(new Date(phpTime).getTime() + 30 * 1000); // Время плюс 30 секунд
        const currentTime = new Date(); // Текущее время

  
        
       
        if (!fuelId || !liters || !columnId || !totalPrice) {
            alert('Ошибка: не все данные были переданы');
            window.location.href = 'purchase_view.php';  
        }
       
        document.getElementById('continue-button').addEventListener('click', function () {
            const cardInput = document.getElementById('card-input').value;
            
           
            if (cardInput.length !== 8) {
                alert('Введите корректные данные карты (8 символов).');
                return;
            }


            document.getElementById('payment-form').innerHTML = '<h1>Обработка...</h1>';

            
            setTimeout(() => {
                
                const isSuccess = Math.random() > 0.2;

                
                if (isSuccess) {
                    saveSaleData();
                } else {
                    updateColumnStatus(columnId);
                    displayResult('Оплата не прошла', false);
                    updateLiters(liters, fuelId);
                }
           }, 3000);
        });
        if (currentTime > newTime) {
            document.body.innerHTML = "<h1>Время вышло. Оплата невозможна. Через 3 секунды вы будете перенаправлены на главный экран.</h1>";
            updateColumnStatus(columnId);
            updateLiters(liters, fuelId);
            setTimeout(function() {
                window.location.href = 'dashboard_view.php';
            }, 3000);
        }
       
        function saveSaleData() {
            fetch('save_sale.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    fuelId: fuelId,
                    liters: liters,
                    totalPrice: totalPrice,
                    columnId: columnId,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayResult('Оплата прошла успешно! Идет заправка...', true);
                    setTimeout(() => {
                        displayResult('Заправка прошла успешно', true); // После задержки показываем сообщение "Идет заправка"
                    }, 5000);
                    updateColumnStatus(columnId);
                } else {
                    alert('Ошибка записи в базу данных: ' + data.error);
                    updateColumnStatus(columnId);
                }
            })
            .catch(error => {
                alert('Ошибка при отправке данных на сервер: ' + error.message);
                updateColumnStatus(columnId);
            });
        }
        function updateColumnStatus(columnId) {
            fetch('update_column_status_free.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ columnId: columnId })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Ошибка: ' + (data.error || 'Не удалось обновить статус колонки'));
                    }
                })
                .catch(error => {
                    alert('Ошибка сервера: ' + error.message);
                });
        }
        function updateLiters(liters, fuelId){
            fetch('update_liters_plus.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ liters: liters, fuelId: fuelId })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Ошибка: ' + (data.error || 'Не удалось обновить статус колонки'));
                    }
                })
                .catch(error => {
                    alert('Ошибка сервера: ' + error.message);
                });
        }
        function displayResult(message, isSuccess) {
            document.body.innerHTML = `
                <div id="payment-form">
                    <h1>${message}</h1>
                    <button id="dashboard-button">Вернуться на главный экран</button>
                </div>
            `;

            
            const dashboardButton = document.getElementById('dashboard-button');
            if (dashboardButton) {
                dashboardButton.addEventListener('click', function () {
                    window.location.href = 'dashboard_view.php';
                });
            } else {
                console.error('Кнопка с ID "dashboard-button" не найдена!');
            }
        }
        
    </script>
</body>
</html>

