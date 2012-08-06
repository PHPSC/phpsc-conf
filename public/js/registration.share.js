$(document).ready(function () {
	$('#shareConfirm').click(function () {
		$('#shareButtons button').prop('disabled', true);
		$('#shareMsg').removeClass('alert-error')
					  .removeClass('alert-success');
		
		$.ajax(
			{
				url: baseUrl + '/registration/share',
				type: 'post',
				success: function (result)
				{
					if (result.error) {
						$('#shareMsg h4').html(result.error);
						$('#shareMsg').addClass('alert-error')
									  .fadeIn()
									  .delay(1500)
								  	  .fadeOut(
							  			  'slow',
							  			  function ()
							  			  {
							  				  $('#shareButtons button').prop('disabled', false);
						  				  }
						  			  );
						
						return ;
					}
					
					$('#shareMsg h4').html('Tweet enviado com sucesso!');
					$('#shareMsg').addClass('alert-success')
								  .fadeIn()
								  .delay(1500)
								  .fadeOut(
									  'slow',
									  function ()
									  {
										  $('#share').modal('hide');
									  }
								  );
				}
			}
		);
	});
});