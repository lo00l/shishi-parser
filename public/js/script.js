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

    $('[data-action=save]').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        btn.prop('disabled', true);
        var data = {};
        $('[data-id=' + btn.data('id') + '][data-attribute]').each(function(index, el) {
            data[$(el).data('attribute')] = $(el).val();
        });
        $.ajax({
            url: btn.attr('href'),
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                if (data['success']) {
                    alert('Изменения сохранены');
                    btn.prop('disabled', false);
                } else {
                    alert('Ошибка сохранения: ' + data.error);
                }
            },
            error: function() {
                alert('Ошибка сохранения');
                btn.prop('disabled', false);
            }
        });
    });

    $('[data-action=save-all]').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var data = {};
        $('tr[data-id]').each(function(index, tr) {
            var row = {};
            $('[data-id=' + $(tr).data('id') + '][data-attribute]').each(function(index, el) {
                row[$(el).data('attribute')] = $(el).val();
            });
            data[$(tr).data('id')] = row;
        });

        $.ajax({
            url: btn.attr('href'),
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                if (data['success']) {
                    alert('Изменения сохранены');
                    btn.prop('disabled', false);
                } else {
                    alert('Ошибка сохранения: ' + data.error);
                }
            },
            error: function() {
                alert('Ошибка сохранения');
                btn.prop('disabled', false);
            }
        });
    });
});
