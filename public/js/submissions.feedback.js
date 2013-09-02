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
	
	if ($('#talkList').length == 0) {
		$('#thanks').fadeIn('slow');
	}
});

function submitFeedback(id, likes, elementToRemove)
{
	$.ajax(
		{
			url: baseUrl + 'call4papers/feedback',
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
				
				if (response.data.likesCount > 0) {
					$('#shareFeedback').fadeIn('slow');
				}
			}
		}
	);
}