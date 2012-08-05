(function($) {
	var drawAddress = function (placeholder, place)
	{
		var address = place.toString().split(',');
		
		if (address.length > 1) {
			var street = address[0];
			var district = address[1].split(' - ')[1].trim();
			var city = address[2].trim();
			
			placeholder.html(
				'<i class="icon-map-marker"></i> ' + street + ', '
				+ district + ', ' + city
			);
			
			return ;
		}
		
		placeholder.html(address);
	};
	
	var appendMarker = function (map, location, title, image, width, height)
	{
		var icon = new google.maps.MarkerImage(
			image,
	      	new google.maps.Size(width, height),
	      	new google.maps.Point(0, 0)
		);

		new google.maps.Marker(
			{
				position: location,
				title: title,
				animation: google.maps.Animation.DROP,
				map: map,
				icon: icon
			}
		);
	};
	
	var createMap = function (
		obj,
		zoom,
		location,
		showMapTypeControls,
		showNavigationControls,
		disableDefaultUI
	) {
		return new google.maps.Map(
			obj,
			{
				zoom:zoom,
				center: location,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				mapTypeControl: showMapTypeControls,
				disableDefaultUI: disableDefaultUI,
				navigationControl: showNavigationControls,
				navigationControlOptions: {
					style: google.maps.NavigationControlStyle.SMALL
				}
			}
		);
	};
	
	var createLocation = function (lat, lng)
	{
		return new google.maps.LatLng(lat, lng);
	};
	
	var getAddress = function (location, placeholder)
	{
		var geocoder = new google.maps.Geocoder();
		
	    geocoder.geocode(
			{
				latLng: location
			},
			function (responses)
			{
		        if (responses && responses.length > 0) {
		        	drawAddress(
	        			placeholder,
	    				responses[0].formatted_address
	    			);
		        	
		        	return ;
		        }
		        
		        drawAddress(
	        		placeholder,
    				'Address not found'
    			);
			}
		);
	};
	
	$.fn.drawGoogleMap = function (options)
	{
		var settings = $.extend(
			{
				lat: 0,
				lng: 0,
				zoom: 11,
				marker: {
					image: null,
					width: null,
					height: null,
					title: 'Location'
				},
				addressPlaceholder: null,
				showMapTypeControls: false,
				showNavigationControls: true,
				disableDefaultUI: true
			},
			options
		);
		
		var location = createLocation(settings.lat, settings.lng);
		var map = createMap(
			document.getElementById(this.prop('id')),
			settings.zoom,
			location,
			settings.showMapTypeControls,
			settings.showNavigationControls,
			settings.disableDefaultUI
		);
		
		if (settings.marker.image && settings.marker.width
			&& settings.marker.height) {
			appendMarker(
				map,
				location,
				settings.marker.title,
				settings.marker.image,
				settings.marker.width,
				settings.marker.height
			);
		}
		
		if (settings.addressPlaceholder) {
			getAddress(
				location,
				settings.addressPlaceholder
			);
		}
		
		return this;
	};
})(jQuery);