function displayBlankForm()
{
    $('#addOrEdit #mainButton').text('Salvar');
    $('.companyData').removeClass('hide');
    $('.supporterData').removeClass('hide');
    
    $('#addOrEdit form').prop('enctype', 'multipart/form-data');
    $('#addOrEdit form').unbind('submit');
    $('#addOrEdit form input, #addOrEdit form textarea').prop('required', true);
    $('#phone, #twitterId, #fanpage').prop('required', false);
    
    $('#addOrEdit form').submit(function () {
        $(this).ajaxSubmit(
            {
                url: baseUrl + 'supporters',
                type: 'POST',
                dataType: 'json',
                success: function (supporter) {
                    displayMsg(
                        'Apoiador cadastrado com sucesso', 
                        'Obrigado ' + supporter.name + '!', 
                        false
                    );
                    
                    $('#addOrEdit').modal('hide');
                    
                    setTimeout(
                        function()
                        {
                            window.location.reload();
                        },
                        1500
                    );
                },
                error: function (xhr, status, error) {
                    displayMsg(
                        'Erro ao cadastrar apoiador!', 
                        xhr.responseJSON.message, 
                        true
                    );
                    
                    $('#addOrEdit').modal('hide');
                    
                    setTimeout(
                        function()
                        {
                            resetMsg();
                        },
                        2000
                    );
                }
            }
        );
        
        return false
    });
}

function openEvaluation(talk)
{
    $('#evaluation .modal-header h3 span').text(talk.type.toLowerCase());
    $('#evaluation .modal-header h3 small').text(talk.title);
    $('#evaluation #speakers label').text(talk.speakers.length == 1 ? 'Palestrante' : 'Palestrantes');
    $('#evaluation #shortDescription').html(talk.shortDescription.replace(/\n/g, '<br />'));
    $('#evaluation #longDescription').html(talk.longDescription.replace(/\n/g, '<br />'));
    
    for (var i = 0; i < talk.speakers.length; ++i) {
        $('#evaluation #speakers').append(
            '<div style="' + (i > 0 ? 'margin-top: 10px' : '') + '">'
                + '<img src="' + talk.speakers[i].avatar + '" alt="'
                    + talk.speakers[i].name + '" class="img-thumbnail" width="55"> '
                + talk.speakers[i].name
            + '</div>'
        );
    }
    
    showTalkSummary(talk);
    
    $('#evaluation').modal('show');
}

function showTalkSummary(talk)
{
    $.ajax(
        {
            url: baseUrl + 'talk/' + talk.id + '/summary',
            type: 'GET',
            dataType: 'json',
            success: function (summary) {
                $('#evaluation #community-evaluation').html(
                    '<div class="well">'
                        + '<label>Votos da comunidade</label>'
                        + '<div class="row">'
                            + '<div class="col-md-6">'
                                + '<span class="label label-info">'
                                    + '<span class="glyphicon glyphicon-thumbs-up"></span>'
                                + '</span> ' + summary.likes + '</div>'
                            + '<div class="col-md-6">'
                                + '<span class="label label-warning">'
                                + '<span class="glyphicon glyphicon-thumbs-down"></span>'
                            + '</span> ' + summary.dislikes + '</div>'
                        + '</div>'
                    + '</div>'
                );
                $('#evaluation #community-evaluation').fadeIn('slow');
            }
        }
    );
}

$(document).ready(function () {
    $('a.btn.btn-xs.btn-info[id|="evaluate"]').click(function () {
        var id = this.id.split('-')[1];
        
        $.ajax(
            {
                url: baseUrl + 'talk/' + id,
                type: 'GET',
                dataType: 'json',
                success: function (talk) {
                    openEvaluation(talk);
                }
            }
        );
        
        return false;
    });
    
    $('#evaluation').on('hidden.bs.modal', function () {
        $('#evaluation .modal-header h3 span').text('');
        $('#evaluation .modal-header h3 small').text('');
        $('#evaluation #speakers').html('<label></label>');
        $('#evaluation #community-evaluation').html('').css('display', 'none');
    });
});