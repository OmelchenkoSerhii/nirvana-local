import $ from 'jquery'

function maps() {

    /**
     * initMap
     *
     * Renders a Google Map onto the selected jQuery element
     *
     * @date    22/10/19
     * @since   5.8.6
     *
     * @param   jQuery $el The jQuery element.
     * @return  object The map instance.
     */
    function initMap($el) {

        // Find marker elements within map.
        var $markers = $el.find('.marker');
        var $eventMarkers = $el.find('.eventmarker');
        var $eventStartMarkers = $el.find('.event-start-marker');
        var $eventFinishMarkers = $el.find('.event-finish-marker');

        // Create gerenic map.
        var mapArgs = {
            zoom: $el.data('zoom') || 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map($el[0], mapArgs);

        // Add markers.
        map.markers = [];
        $markers.each(function () {
            initMarker($(this), map);
        });
        $eventMarkers.each(function () {
            initMarkerEvent($(this), map);
        });
        $eventStartMarkers.each(function () {
            initMarkerStart($(this), map);
        });
        $eventFinishMarkers.each(function () {
            initMarkerFinish($(this), map);
        });

        // Center map based on markers.
        centerMap(map);

        // Return map instance.
        return map;
    }

    /**
     * initMarker
     *
     * Creates a marker for the given jQuery element and map.
     *
     * @date    22/10/19
     * @since   5.8.6
     *
     * @param   jQuery $el The jQuery element.
     * @param   object The map instance.
     * @return  object The marker instance.
     */
    function initMarker($marker, map) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position: latLng,
            icon: customjs_ajax_object.theme_url + '/assets/images/map/hotel-icon.png',
            map: map
        });

        // Append to reference for later use.
        map.markers.push(marker);

        // If marker contains HTML, add it to an infoWindow.
        if ($marker.html()) {

            // Create info window.
            var infowindow = new google.maps.InfoWindow({
                content: $marker.html()
            });

            // Show info window when marker is clicked.
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
        }
    }

    function initMarkerHotels($marker, map) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position: latLng,
            icon: customjs_ajax_object.theme_url + '/assets/images/map/hotel-icon.png',
            map: map
        });

        // Append to reference for later use.
        map.markers.push(marker);

        // Show info window when marker is clicked.
        google.maps.event.addListener(marker, 'click', function () {
            $('#popup-hotel-location-' + $marker.data('hotel')).fadeIn()
        });
    }

    function initMarkerEvent($marker, map) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position: latLng,
            icon: customjs_ajax_object.theme_url + '/assets/images/map/event-icon.png',
            map: map
        });

        // Append to reference for later use.
        map.markers.push(marker);
    }

    function initMarkerStart($marker, map) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position: latLng,
            icon: customjs_ajax_object.theme_url + '/assets/images/map/start-icon.png',
            map: map
        });

        // Append to reference for later use.
        map.markers.push(marker);
    }

    function initMarkerFinish($marker, map) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position: latLng,
            icon: customjs_ajax_object.theme_url + '/assets/images/map/finish-icon.png',
            map: map
        });

        // Append to reference for later use.
        map.markers.push(marker);
    }

    /**
     * centerMap
     *
     * Centers the map showing all markers in view.
     *
     * @date    22/10/19
     * @since   5.8.6
     *
     * @param   object The map instance.
     * @return  void
     */
    function centerMap(map) {

        // Create map boundaries from all map markers.
        var bounds = new google.maps.LatLngBounds();
        map.markers.forEach(function (marker) {
            bounds.extend({
                lat: marker.position.lat(),
                lng: marker.position.lng()
            });
        });

        // Case: Single marker.
        if (map.markers.length == 1) {
            map.setCenter(bounds.getCenter());

            // Case: Multiple markers.
        } else {
            map.fitBounds(bounds);
        }
    }

    // Render maps on page load.
    $('.acf-map').each(function () {
        var map = initMap($(this));
    });

    $('.acf-hotels-map').each(function () {
        var map = initMap($(this));
        // Add markers.
        map.markers = [];
        $('.js-hotel-location').each(function () {
            initMarkerHotels($(this), map);
        });
        $('.js-event-location').each(function () {
            initMarkerEvent($(this), map);
        });
        $('.js-event-start-marker').each(function () {
            initMarkerStart($(this), map);
        });
        $('.js-event-finish-marker').each(function () {
            initMarkerFinish($(this), map);
        });
        setTimeout(function(){
            centerMap(map);
            map.setZoom(13);
        } , 1000)
    });

}


export { maps }
