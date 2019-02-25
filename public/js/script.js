$(document).ready(function() {
    $("#run_parser").click(function() {
        if (confirm('Вы действительно хотите запланировать запуск парсера?')) {
            $(this).addClass('disabled');
            $.post('/run', function(data) {
                if (data['success']) {
                    alert('Парсер запустится в течение 5 минут');
                    location.reload();
                } else {
                    alert('Ошибка: ' + data.error);
                }
            }, 'json');
        }
    });
});