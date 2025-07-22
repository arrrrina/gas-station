<?php
include 'session_check.php';
date_default_timezone_set('Europe/Moscow');
$_SESSION['time'] = date('Y-m-d H:i:s');
$_SESSION['time'] = date('Y-m-d H:i:s', strtotime($_SESSION['time']) + 3600)
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Цена</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background:rgb(255, 245, 245);
        }
        #price-info {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <form id="price-info">
        <h1>Информация о стоимости</h1>
        <p id="fuel-info">Загрузка данных...</p>
        <p id="price"></p>
        <input type="hidden" id="total-price" name="totalPrice" value="">
        <button class="sale" id="pay-button" type="button">Оплатить</button>
        
    </form>
 
    <script>
        
        // Получение данных из строки запроса
        const urlParams = new URLSearchParams(window.location.search);
        const fuelId = urlParams.get('fuelId');
        const numberOfLiters = urlParams.get('numberOfLiters');
        const columnId = urlParams.get('columnId');

        if (!fuelId || !numberOfLiters) {
            document.getElementById('fuel-info').textContent = 'Ошибка: недостаточно данных для расчета.';
            throw new Error('fuelId или numberOfLiters отсутствует в параметрах URL');
        }
        let totalPrise;
        // Отправка запроса для получения цены за литр
        fetch(`price.php?fuelId=${fuelId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const priceOfLiter = data.price_of_liter;
                    totalPrice = (priceOfLiter * numberOfLiters).toFixed(2);
                    document.getElementById('fuel-info').textContent = 
                        `Общая стоимость: ${totalPrice} руб`;
                } else {
                    document.getElementById('fuel-info').textContent = 
                        `Ошибка: ${data.error}`;
                }
            })
            .catch(error => {
                document.getElementById('fuel-info').textContent = 
                    `Ошибка загрузки данных: ${error.message}`;
            });
            document.getElementById('pay-button').addEventListener('click', function () {
                const queryString = new URLSearchParams({
                            fuelId: fuelId,
                            numberOfLiters: numberOfLiters,
                            totalPrice: totalPrice,
                            columnId: columnId
                        }).toString();    
                window.location.href = `payment_view.php?${queryString}`;
            });
           
            
    </script>
</body>
</html>
