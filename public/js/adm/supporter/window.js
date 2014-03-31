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

function displayMsg(title, description, isError)
{
    $('#confirmationMsg .alert h4').html(title);
    $('#confirmationMsg .alert span').html(description);
    $('#confirmationMsg .alert').addClass(isError ? 'alert-danger' : 'alert-success');
    $('#confirmationMsg').css('display', 'none').removeClass('hide').fadeIn();
}

function resetMsg()
{
    $('#confirmationMsg .alert').removeClass('alert-danger')
                                .removeClass('alert-success')
                                .removeClass('alert-info');
    
    $('#confirmationMsg').addClass('hide');
}

function populateForm(company)
{
    $('#addOrEdit #mainButton').text('Salvar');
    $('.companyData').removeClass('hide');
    $('.companyLogo').addClass('hide');
    $('.supporterData').removeClass('hide');
    
    $('#name').val(company.name);
    $('#email').val(company.email);
    $('#phone').val(company.phone);
    $('#website').val(company.website);
    $('#twitterId').val(company.twitterId);
    $('#fanpage').val(company.fanpage);
    
    $('.companyData input').prop('disabled', true);
    
    $('#addOrEdit form').unbind('submit');
    $('#addOrEdit form').submit(function () {
        return false;
        
        
        $(this).ajaxSubmit(
            {
                url: baseUrl + 'supporters',
                type: 'POST',
                dataType: 'json'
            }
        );
    });
}

$(document).ready(function () {
    var defaultAction = function () {
        var socialId = $('#socialId').val();
        
        if (socialId.length < 14) {
            $('#socialId').focus();
            return ;
        }
        
        $('#socialId').prop('readonly', true);
        $('.companyData').addClass('hide');
        $('.supporterData').addClass('hide');
        
        $.ajax(
            {
                url: baseUrl + 'companies',
                type: 'GET',
                dataType: 'json',
                data: {
                    socialId: socialId 
                },
                success: function (response)
                {
                    if (response.length == 0) {
                        displayBlankForm();
                        
                        return ;
                    }
                    
                    populateForm(response[0]);
                }
            }
        );

        return false;
    };
    
    $('#addOrEdit').on('hidden.bs.modal', function () {
        $('#addOrEdit form').prop('enctype', 'application/x-www-form-urlencoded');
        $('#addOrEdit form').unbind('submit');
        $('#addOrEdit form').submit(defaultAction);
        
        $('.companyData').addClass('hide');
        $('.supporterData').addClass('hide');
        $('.companyData input').val('');
        $('.supporterData textarea').val('');
        $('#addOrEdit form input').prop('required', false);
        $('#addOrEdit form textarea').prop('required', false);
        $('#socialId').prop('readonly', false).val('').prop('required', true);
        
        $('#addOrEdit #mainButton').html(
            '<span class="glyphicon glyphicon-search"></span> Buscar'
        );
    });
    
    $('#addOrEdit form').submit(defaultAction);
    
    $('#addSupporter').click(function () {
        $('#addOrEdit #title').text('Novo apoiador');
        $('#addOrEdit').modal('show');
    });
});