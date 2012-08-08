$(document).ready(function () {
	$('#payConfirm').click(function () {
		$('#payButtons button').prop('disabled', true);
		$('#payMsg').removeClass('alert-error')
					.removeClass('alert-success');
		
		$.ajax(
			{
				url: baseUrl + '/registration/resendPayment',
				type: 'post',
				success: function (response)
				{
					if (response.error) {
						$('#payMsg h4').html(response.error);
						$('#payMsg').addClass('alert-error')
									.fadeIn()
									.delay(1500)
								  	.fadeOut(
							  			'slow',
							  			function ()
							  			{
							  				$('#payButtons button').prop('disabled', false);
						  				}
						  			);
						
						return ;
					}
					
					$('#payMsg h4').html('Pagamento solicitado com sucesso, em breve você será redirecionado');
					$('#payMsg').addClass('alert-success').fadeIn();
					
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
	});
});