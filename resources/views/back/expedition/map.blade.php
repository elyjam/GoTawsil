<style>
#pm_details td {
    background-color: rgba(242, 242, 242, .5);
}

#operations th {
    background-color: rgba(242, 242, 242, .5);
}
#dettes th {
    background-color: rgba(242, 242, 242, .5);
}
#revenus th {
    background-color: rgba(242, 242, 242, .5);
}

#map {
    height: 600px;
    width: 100%;
}

</style>


<div id="map"></div>

<script>




function initMap() {
    function pinSymbol(color) {
        return {
            path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
            fillColor: color,
            fillOpacity: 1,
            strokeColor: color,
            strokeWeight: 2,
            scale: 1,
        };
    }

    var myLatLng = {
        lat: {{$record->lat}},
        lng: {{$record->long}}
    };
    @if(strlen($record->lat)>4)
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: myLatLng,
        styles: [{
                featureType: "administrative.country",
                elementType: "labels",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "administrative.country",
                elementType: "geometry.fill",
                stylers: [{
                    visibility: "off"
                }]
            },
            {
                featureType: "administrative.country",
                elementType: "geometry.stroke",
                stylers: [{
                    visibility: "off"
                }]
            }
        ]
    });
    @endif


    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map
    });
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCB7WycjMHKxa0XDtONW0r7zFjS9bbwKbU&callback=initMap" async
    defer></script>

