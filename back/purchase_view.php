<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Покупка</title>
    <link rel="stylesheet" href="../style/style_purchase.css">
</head>
<body>
    <form id="fuel-form" >
        <h1>Покупка</h1>
        <label for="column-select">Колонка:</label>
        <select id="column-select" name="column">
            <option value="" disabled hidden></option>
        </select>

        <label for="fuel-type">Бензин:</label>
        <select id="fuel-type" name="fuel-type" >
            <option value="" disabled hidden></option>
        </select>

        <label for="liters">Количество литров:</label>
        <input type="number" id="liters" name="liters" placeholder="Введите количество" min ="1" oninput="validateInput(this)" disabled>

    
        <button class="sale-inf">Продолжить</button>
        
    </form>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const stationId = urlParams.get('stationId');
    
        if (!stationId) {
            alert('Ошибка: stationId не указан');
        }
    
       
        function loadColumns() {
            if (!stationId) return;
    
            fetch(`purchase_column.php?stationId=${stationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('column-select');
                        select.innerHTML = '<option value="" disabled selected hidden>Выберите колонку</option>';
                        data.columns.forEach(columns => {
                            const option = document.createElement('option');
                            option.value = columns.id;
                            option.textContent = columns.number;
                            select.appendChild(option);
                        });
                    } else {
                        alert('Ошибка: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Ошибка загрузки данных: ' + error.message);
                });
        }
    
       
        function loadFuel(columnId) {
            const fuelTypeSelect = document.getElementById('fuel-type');
            fuelTypeSelect.innerHTML = '<option value="" disabled selected hidden>Выберите топливо</option>'; // Очистить предыдущие
    
            if (!columnId) {
                fuelTypeSelect.disabled = true;
                return;
            }
    
            fetch(`purchase_fuel.php?columnId=${columnId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                       
                        data.fuel.forEach(fuel => {
                            const option = document.createElement('option');
                            option.value = fuel.id; 
                            option.textContent = fuel.name;
                            fuelTypeSelect.appendChild(option);
                        });
    
                        fuelTypeSelect.disabled = false;
                    } else {
                        alert('Ошибка: ' + data.error);
                        fuelTypeSelect.disabled = true;
                    }
                })
                .catch(error => {
                    alert('Ошибка загрузки топлива: ' + error.message);
                });
        }
        function loadLiter(fuelId) {
            const liters = document.getElementById('liters');
    
            if (!fuelId) {
                liters.disabled = true;
                liters.max = "";
                return;
            }
    
            fetch(`purchase_number_liter.php?fuelId=${fuelId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        liters.max = data.number_of_liters; 
                        liters.disabled = false;
                        
                        
                    } else {
                        alert('Ошибка: ' + data.error);
                        liters.disabled = true;
                        liters.max = "";
                    }
                })
                .catch(error => {
                    alert('Ошибка загрузки топлива: ' + error.message);
                    liters.disabled = true;
                    liters.max = ""; 
                });
        }
        function validateInput(input) {
            const max = parseFloat(input.max); 
            const value = parseFloat(input.value); 
            if (!isNaN(max) && value > max) {
                input.value = max;
                alert(`Максимальное количество литров: ${max}`);
            }
        }
       
        document.querySelector('.sale-inf').addEventListener('click', function (event) {
            event.preventDefault(); 

            const fuelId = document.getElementById('fuel-type').value;
            const liters = parseInt(document.getElementById('liters').value, 10);  // Преобразуем в число
            const columnId = document.getElementById('column-select').value;

            // if (!fuelId || !liters || !columnId) {
            //     alert('Заполните все поля перед продолжением!');
            //     return;
            // }
            // const queryString = new URLSearchParams({
            //                 fuelId: fuelId,
            //                 numberOfLiters: liters,
            //                 columnId: columnId
            //             }).toString(); 
           setTimeout(() => {
            // window.location.href = `price_view.php?${queryString}`;

            fetch('update_column_status_nofree.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ columnId: columnId, fuelId: fuelId, liters: liters })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const queryString = new URLSearchParams({
                            fuelId: fuelId,
                            numberOfLiters: liters,
                            columnId: columnId
                        }).toString();
                        window.location.href = `price_view.php?${queryString}`;
                    } else {
                        if (data.error === 'Колонка занята или не найдена') {
                            alert('Ошибка: Колонка занята или не найдена. Попробуйте выбрать другую колонку.');
                        } else if (data.error === 'Недоступное количество литров') {
                            alert('Ошибка: Недоступное количество литров. Пожалуйста, выберите меньшее количество.');
                        } else {
                            alert('Ошибка: ' + (data.error || 'Не удалось обновить статус колонки'));
                        }
                        location.reload();
                    }
                })
                .catch(error => {
                    alert('Ошибка сервера: ' + error.message);
                });
                
            
           }, 3000);
        });
   
       
      
        loadColumns();
        loadFuel();
    
        document.getElementById('column-select').addEventListener('change', function() {
            const columnId = this.value;
            loadFuel(columnId);
        });
        document.getElementById('fuel-type').addEventListener('change', function() {
            const fuelId = this.value;
            loadLiter(fuelId); 
        });
        
    </script>
    
</body>
</html>
