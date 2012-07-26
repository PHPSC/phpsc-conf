$(document).ready(function() {
	$('.alert').hide();
	
	$('#userForm').submit(function () {
		$('#userForm button').prop('disabled', true);
		$('html, body').animate({scrollTop:0}, 'fast');
		
		$.ajax(
			{
				url: $('#userForm').prop('action'),
				type: $('#userForm').prop('method').toUpperCase(),
				data: {
					name: $('#name').val(),
					email: $('#email').val(),
					githubUser: $('#githubUser').val(),
					bio: $('#bio').val()
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
						
						$('#userForm button').prop('disabled', false);
						
						return ;
					}
					
					$('.alert h4').html('Atualização realizada com sucesso');
					$('.alert span').html('@' + response.data.twitterUser + ', seus dados foram atualizados!');
					$('.alert').addClass('alert-success').fadeIn().delay(2000).fadeOut(
						'fast',
						function ()
						{
							$('#userForm button').prop('disabled', false);
						}
					);
				}
			}
		);
		
		return false;
	});
});