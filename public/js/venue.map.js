$(document).ready(function () {
	var location = new google.maps.LatLng(lat,lng);
	
	var mapContato = new google.maps.Map(
		document.getElementById('mapEvento'),
		{
			zoom:16,
			center: location,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl:false,
			disableDefaultUI: true,
			navigationControl: true,
			navigationControlOptions: {
				style: google.maps.NavigationControlStyle.SMALL
			}
		}
	);

	var icon = new google.maps.MarkerImage(
		baseUrl + '/img/pin.png',
      	new google.maps.Size(62, 80),
      	new google.maps.Point(0, 0)
	);

	new google.maps.Marker(
		{
			position: location,
			title: 'Local do Evento',
			animation: google.maps.Animation.DROP,
			map: mapContato,
			icon: icon, 
		}
	);

	geocodePosition(location);
});

function geocodePosition(location) 
{
	var geocoder = new google.maps.Geocoder();
	
    geocoder.geocode(
		{
			latLng: location
		},
		function (responses) {
	        if (responses && responses.length > 0) {
	        	updateMarkerStatus(responses[0].formatted_address);
	    	} else {
	    		updateMarkerStatus(';( Endereço não encontrado.');
	        }
		}
	);
}

function updateMarkerStatus(local)
{
	var address = local.toString().split(',');
	var street = address[0];
	var city = address[2].trim();
	
	document.getElementById('address').innerHTML = '<i class="icon-map-marker"></i> ' + street + ', ' + city;
}