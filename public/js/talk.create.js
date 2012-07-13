$(document).ready(function() {
	$('#talkForm').submit(function () {
		$('#talkForm button').prop('disabled', true);
		$('html, body').animate({scrollTop:0}, 'fast');
		
		$.ajax(
			{
				url: $('#talkForm').prop('action'),
				type: $('#talkForm').prop('method').toUpperCase(),
				data: {
					title: $('#title').val(),
			        type: $('#type').val(),
			        shortDescription: $('#shortDescription').val(),
			        longDescription: $('#longDescription').val(),
			        complexity: $('#complexity').val(),
			        tags: $('#tags').val()
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
						
						$('#talkForm button').prop('disabled', false);
						
						return ;
					}
					
					$('.alert h4').html('Cadastro realizado com sucesso');
					$('.alert span').html('A proposta "' + response.data.title + '" foi cadastrada com sucesso!');
					$('.alert').addClass('alert-success').fadeIn();
					
					setTimeout(
						function()
						{
							window.location = baseUrl + '/call4papers/submissions';
						},
						1000
					);
				}
			}
		);
		
		return false;
	});
});