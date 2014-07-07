$(document).ready(function () {
	var quotas = [
	    {id: 1, label: "Platina", rowCss: "platinum", perRow: 2, logo: {w: 250, h: 120}, details: true},
	    {id: 2, label: "Ouro", rowCss: "gold", perRow: 4, logo: {w: 200, h: 100}, details: false},
	    {id: 3, label: "Prata", rowCss: "silver", perRow: 6, logo: {w: 160, h: 80}, details: false},
	    {id: 4, label: "Bronze", rowCss: "brass", perRow: 12, logo: {w: 80, h: 60}, details: false}
    ];
	
	findSponsors(0);
	
	function findSponsors(i)
	{
		if (i >= quotas.length) {
			$('div.sponsors h4')[0].style.marginTop = 0;
			$('#sponsorsContainer').removeClass('hide');
			return;
		}
		
		var quota = quotas[i];
		
		$.ajax(
			{
				url: baseUrl + 'sponsors',
				dataType: 'json',
				data: {'quota': quota.id},
				type: 'GET',
				success: function (sponsors) {
					renderSponsors(quota, sponsors);
					findSponsors(i + 1);
				}
			}
		);
	}
	
	function renderSponsors(quota, sponsors)
	{
		if (sponsors.length == 0) {
			return ;
		}
		
		var element = $('<div class="row sponsors ' + quota.rowCss + '"></div>');
		element.append('<h4 class="label label-default">' + quota.label + '</h4>')
		
		for (var i = 0; i < sponsors.length; ++i) {
			var sponsor = sponsors[i];
			
			if (i % quota.perRow == 0 && element.children().length > 0) {
				$('#sponsorsContainer').append(element);
				element = $('<div class="row sponsors ' + quota.rowCss + '"></div>');
			}
			
			element.append(
				'<div class="col-md-' + (12 / quota.perRow) + '">' + getSponsorContent(quota, sponsor) + '</div>'
			);
		}
		
		if (element.children().length > 0) {
			$('#sponsorsContainer').append(element);
		}
	}
	
	function getSponsorContent(quota, sponsor)
	{
		var link = '<a href="' + sponsor.website + '" target="_blank" title="' + sponsor.name + '">'
						+ '<img src="' + baseUrl + 'sponsor/' + sponsor.id + '?w=' + quota.logo.w + '&h='
						+ quota.logo.h + '" alt="' + sponsor.name + '">'
					+ '</a>';
		
		if (!quota.details) {
			return link;
		}
		
		return '<div class="col-md-6" style="text-align: center">' + link + '</div>'
				+ '<div class="col-md-6"><h5>' + sponsor.name + '</h5><p>' + sponsor.details + '</p></div>';
	}
});
