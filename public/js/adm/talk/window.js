function openEvaluation(talk)
{
    $('#evaluation .modal-header h3 span').text(talk.type.toLowerCase());
    $('#evaluation .modal-header h3 small').text(talk.title);
    $('#evaluation #speakers label').text(talk.speakers.length == 1 ? 'Palestrante' : 'Palestrantes');
    $('#evaluation #shortDescription').html(talk.shortDescription.replace(/\n/g, '<br />'));
    $('#evaluation #longDescription').html(talk.longDescription.replace(/\n/g, '<br />'));
    $('#evaluation #talkId').val(talk.id);
    
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
    getEvaluation(talk);
    
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

function getEvaluation(talk)
{
    $.ajax(
        {
            url: baseUrl + 'evaluations',
            type: 'GET',
            dataType: 'json',
            data: {
                'talkId': talk.id,
                'evaluator': 0
            },
            success: function (evaluations) {
                if (evaluations.length == 0) {
                    return ;
                }
                
                $('#evaluation #evaluationId').val(evaluations[0].id);
                
                fillForm(
                    evaluations[0].quality,
                    evaluations[0].relevance,
                    evaluations[0].quality,
                    evaluations[0].notes
                );
            }
        }
    );
}

function fillForm(originality, relevance, quality, notes)
{
    $('#evaluation #originality').val(originality);
    $('#evaluation #relevance').val(relevance);
    $('#evaluation #quality').val(quality);
    $('#evaluation #notes').val(notes);
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
        $('#evaluation #talkId').val('');
        $('#evaluation #evaluationId').val('');
        
        fillForm('', '', '', '');
    });
    
    $('#evaluation form').submit(function () {
        var id = $('#evaluation #evaluationId').val();
        var isRegistered = id != '';
        var type = isRegistered ? 'PUT' : 'POST';
        var uri = isRegistered ? baseUrl + 'evaluation/' + id : baseUrl + 'evaluations';
        
        $(this).ajaxSubmit(
            {
                url: uri,
                type: type,
                dataType: 'json',
                success: function (evaluation) {
                    console.log(evaluation);
                    $('#evaluation').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseJSON.message);
                }
            }
        );
        
        return false
    });
});