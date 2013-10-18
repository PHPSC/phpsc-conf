$(document).ready(function () {
	$.ajax(
		{
			url: baseUrl + 'supporters',
			dataType: 'json',
			type: 'GET',
			success: function (supporters) {
				if (supporters.length == 0) {
					return ;
				}
				
				var element = $('<div class="row"></div>');
				
				for (var i = 0; i < supporters.length; ++i) {
					var supporter = supporters[i];
					
					if (i % 6 == 0 && element.children().length > 0) {
						$('#supportersContainer').append(element);
						element = $('<div class="row"></div>');
					}
					
					element.append(
						'<div class="col-md-2" style="padding-top: 10px; text-align: center; line-height: 80px;">'
					    	+ '<a href="' + supporter.website + '" target="_blank" title="' + supporter.name + '">'
				        		+ '<img src="' + baseUrl + 'supporter/' + supporter.id + '?w=160&h=80" alt="' + supporter.name + '">'
		        			+ '</a>'
						+ '</div>'
					);
				}
				
				if (element.children().length > 0) {
					$('#supportersContainer').append(element);
				}
				
				$('#supportersContainer').removeClass('hide');
			}
		}
	);
});