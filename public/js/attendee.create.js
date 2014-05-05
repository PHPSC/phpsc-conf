$(document).ready(function() {
    function calculateCost()
    {
        var cost = $('#registrationType0').is(':checked')
                   ? $('#registrationCost').data('all-days')
        		   : $('#registrationCost').data('talks-only');
                   
        if ($('#isStudent').is(':checked') && $('#registrationCost').data('student-discount') > 0) {
        	cost -= cost * $('#registrationCost').data('student-discount') / 100;
        }
                   
        $('#registrationCost').text(cost * 100).priceFormat({
            prefix: 'R$ ',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
    }
    
    $('#registrationType0, #registrationType1').click(function () {
    	calculateCost();
    });
    
    $('#isStudent').change(function() {
    	calculateCost();
    	
        if ($(this).is(':checked')) {
            $('#studentRules').modal('show');
            $('#discountCoupon').prop('disabled', true);
            $('#discountCoupon').val('');
            
            return true;
        }
        
        $('#discountCoupon').prop('disabled', false);
        $('#discountCoupon').val('');
    });
    
    $('#revertIsStudent').click(function() {
        $('#discountCoupon').prop('disabled', false);
        $('#discountCoupon').val('');
        $('#isStudent').prop('checked', false);
        calculateCost();
    });
    
    $('#attendeeForm').submit(function () {
        $('#attendeeForm button').prop('disabled', true);
        $('html, body').animate({scrollTop:0}, 'fast');
        
        $.ajax(
            {
                url: $('#attendeeForm').prop('action'),
                type: $('#attendeeForm').prop('method').toUpperCase(),
                data: {
                    isStudent: $('#isStudent').prop('checked'),
                    canAttendAllDays: $('#registrationType0').is(':checked'),
                    discountCoupon: $('#discountCoupon').val()
                },
                success: function(response)
                {
                    $('.alert').removeClass('alert-danger')
                               .removeClass('alert-success')
                               .removeClass('alert-info');
                    
                    if (response.error) {
                        $('.alert h4').html('Erro ao realizar seu cadastro');
                        $('.alert span').html(response.error);
                        $('.alert').addClass('alert-danger').fadeIn();
                        
                        $('#attendeeForm button').prop('disabled', false);
                        
                        return ;
                    }
                    
                    $('.alert h4').html('Cadastro realizado com sucesso');
                    $('.alert span').html('Sua inscrição foi cadastrada com sucesso, em instantes você será redirecionado!');
                    $('.alert').addClass('alert-success').fadeIn();
                    
                    setTimeout(
                        function()
                        {
                            window.location = response.data.redirectTo;
                        },
                        500
                    );
                }
            }
        );
        
        return false;
    });
});