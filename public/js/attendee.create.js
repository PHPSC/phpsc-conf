$(document).ready(function() {
	$('#isStudent').change(function() {
		if ($(this).is(':checked')) {
			$('#regularAttendee').hide();
			$('#studentAttendee').show();
			$('#studentRules').modal('show');
			
			return true;
		}
		
		$('#regularAttendee').show();
		$('#studentAttendee').hide();
	});
	
	$('#revertIsStudent').click(function() {
		$('#regularAttendee').show();
		$('#studentAttendee').hide();
		$('#isStudent').prop('checked', false);
	});
	
	
	$('#attendeeForm').submit(function () {
		$('#attendeeForm button').prop('disabled', true);
		$('html, body').animate({scrollTop:0}, 'fast');
		
		$.ajax(
			{
				url: $('#attendeeForm').prop('action'),
				type: $('#attendeeForm').prop('method').toUpperCase(),
				data: {
					isStudent: $('#isStudent').prop('checked')
				},
				success: function(response)
				{
					$('.alert').removeClass('alert-error')
							   .removeClass('alert-success')
							   .removeClass('alert-info');
					
					if (response.error) {
						$('.alert h4').html('Erro ao realizar seu cadastro');
						$('.alert span').html(response.error);
						$('.alert').addClass('alert-error').fadeIn();
						
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