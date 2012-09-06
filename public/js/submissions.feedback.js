$(document).ready(function () {
	$('a[id|="like"]').click(function () {
		var id = this.id.split('-')[1];
		
		submitFeedback(id, true, $('#talk' + id).parent());
		
		return false;
	});
	
	$('a[id|="dislike"]').click(function () {
		var id = this.id.split('-')[1];
		
		submitFeedback(id, false, $('#talk' + id).parent());
		
		return false;
	});
	
	$('#shareConfirm').click(function (){
		$('#shareButtons button').prop('disabled', true);
		$('#shareMsg').removeClass('alert-error')
					  .removeClass('alert-success');
		
		$.ajax(
			{
				url: baseUrl + '/call4papers/feedback/share',
				type: 'POST',
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
	
	if ($('#talkList').length == 0) {
		$('#thanks').fadeIn('slow');
	}
});

function submitFeedback(id, likes, elementToRemove)
{
	$.ajax(
		{
			url: baseUrl + '/call4papers/feedback',
			type: 'POST',
			data: {
				talkId: id,
				likes: likes ? 1 : 0
			},
			success: function(response)
			{
				if (response.error) {
					alert(response.error);
					
					return false;
				}
				
				elementToRemove.fadeOut(
					'slow',
					function ()
					{
						$(this).remove();
						
						if ($('#talkList').children().length == 0) {
							$('#thanks').fadeIn('slow');
						}
					}
				);
				
				if (response.data.likesCount < 2) {
					$('#likesCount').text(response.data.likesCount + ' submissão');
				} else {
					$('#likesCount').text(response.data.likesCount + ' submissões');
				}
				
				if (response.data.likesCount > 0) {
					$('#shareFeedback').fadeIn('slow');
					$('#shareConfirm').prop('disabled', false);
				}
			}
		}
	);
}