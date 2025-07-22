<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Автозаправочные станции</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style/style_dashboard.css">
</head>
<body>
    <div class="top-bar">
        <div class="title">Автозаправочные станции</div>
        <div class="buttons">
            <button onclick="window.location.href='profile_view.php'">Личный кабинет</button>
            <button onclick="window.location.href='statistic_view.php'">Статистика продаж</button>
            <button onclick="window.location.href='history_view.php'">История просмотров</button>
        </div>
    </div>
        
    </div>
    <div class="form-section">
        <h2>Выберите фирму:<h2></h2>
        <select id="firm" name="firm_id">
            <option value="">Выберите фирму</option>
        </select>
    </div>

    <div id="stations">
        <h2>Адреса станций</h2>
        <ul id="station-list">
        </ul>
    </div>

    <div id="station-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Информация о станции</h2>
            </div>
            <div class="modal-body" id="modal-body">  
            </div>
            <button class="fuel-btn" onclick="openFuelWindow(this)">Заправиться</button>
            <button class="close-btn" onclick="closeModal()">Закрыть</button>
        </div>
    </div>
    
    <div id="fuel-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Выбор топлива</h2>
            </div>
            <div class="modal-body" id="fuel-modal-body">
            </div>
            <button class="close-btn" onclick="closeFuelModal()">Закрыть</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajax({
                url: 'dashboard.php',
                type: 'POST',
                data: { action: 'get_user_role' },
                dataType: 'json',
                success: function (response) {
                    if (!response.isAdmin) {
                        $('button:contains("Статистика продаж")').hide();
                    }
                },
                error: function () {
                    alert('Ошибка при проверке роли пользователя.');
                }
            });
        });
        $(document).ready(function () {
            $.ajax({
                url: 'dashboard.php',
                type: 'POST',
                data: { action: 'get_firms' },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        response.firms.forEach(function (firm) {
                            $('#firm').append(`<option value="${firm.id}">${firm.name}</option>`);
                        });
                    } else {
                        alert('Ошибка загрузки фирм: ' + response.error);
                    }
                },
                error: function () {
                    alert('Произошла ошибка при загрузке списка фирм.');
                }
            });

            $('#firm').on('change', function () {
                const firmId = $(this).val();
                if (firmId) {
                    $.ajax({
                        url: 'dashboard.php',
                        type: 'POST',
                        data: { action: 'get_stations', firm_id: firmId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                const stationList = $('#station-list');
                                stationList.empty(); 
                                response.stations.forEach(function (station) {
                                    stationList.append(`
                                        <li>
                                            <a href="javascript:void(0);" class="station-link" data-id="${station.id}">
                                                ${station.city}, ${station.street} ${station.house}
                                            </a>
                                        </li>
                                    `);
                                });
                            } else {
                                alert('Ошибка загрузки станций: ' + response.error);
                            }
                        },
                        error: function () {
                            alert('Произошла ошибка при загрузке списка станций.');
                        }
                    });
                } else {
                    $('#station-list').empty(); 
                }
            });

           
            $('#station-list').on('click', '.station-link', function () {
                const stationId = $(this).data('id');
                loadStationInfo(stationId); 
            });
        });

        
        function loadStationInfo(stationId) {
            $.ajax({
                url: 'station_info.php',
                type: 'GET',
                data: { id: stationId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const station = response.station;
                        $('#modal-body').html(`
                            <p><strong>Название:</strong> ${station.name}</p>
                            <p><strong>Адрес:</strong> ${station.city}, ${station.street} ${station.house} </p>
                            <p><strong>Время работы:</strong> ${station.openning_time} - ${station.ending_time}</p>
                            <p><strong>Номер:</strong> ${station.phone_number}</p>
                        `);
                        $('#station-modal').fadeIn(); 
                        $('.fuel-btn').attr('data-station-id', stationId);

                        $('#station-modal').fadeIn();
                    } else {
                        alert('Ошибка загрузки данных о станции: ' + response.error);
                    }
                },
                error: function () {
                    alert('Произошла ошибка при загрузке информации о станции.');
                }
            });
        }

        function openFuelWindow(button) {
            const stationId = $(button).attr('data-station-id');
            window.location.href = `purchase_view.php?stationId=${stationId}`;
        }
        function closeModal() {
            $('#station-modal').fadeOut();
        }
    </script>
</body>
</html>
