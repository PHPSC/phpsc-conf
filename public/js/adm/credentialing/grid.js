$(document).ready(function () {
    $('a.btn.btn-xs.btn-info[id|="approve"]').click(function () {
        var id = this.id.split('-')[1];
        
        $.ajax(
            {
                url: baseUrl + 'attendee/' + id,
                type: 'PUT',
                dataType: 'json',
                data: {
                    status: 3
                },
                success: function (response)
                {
                    $('#buttons-' + id).text('');
                    $('#desc-' + id).text('Pagamento confirmado');
                }
            }
        );
        
        return false;
    });
    
    $('a.btn.btn-xs.btn-warning[id|="pay"]').click(function () {
        var id = this.id.split('-')[1];
        
        $.ajax(
            {
                url: baseUrl + 'attendee/' + id,
                type: 'PUT',
                dataType: 'json',
                data: {
                    status: 2
                },
                success: function (response)
                {
                    $('#buttons-' + id).text('');
                    $('#desc-' + id).text('Pagamento confirmado');
                }
            }
        );
        
        return false;
    });
});