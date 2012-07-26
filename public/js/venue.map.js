$(document).ready(function () {
	if (lat && lng) {
		$('#mapEvento').drawGoogleMap(
			{
				lat: lat,
				lng: lng,
				zoom: 18,
				marker: {
					image: baseUrl + '/img/pin.png',
					width: 62,
					height: 80,
					title: 'Local do Evento'
				},
				addressPlaceholder: $('#address')
			}
		);
	}
});