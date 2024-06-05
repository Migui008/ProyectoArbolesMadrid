var enlaceArboles = document.getElementById('enlaceArboles');
var enlaceParques = document.getElementById('enlaceParques');



function initMap() {
    if (typeof latitud !== 'undefined' && typeof longitud !== 'undefined') {
        var parqueLocation = { lat: latitud, lng: longitud };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: parqueLocation
        });

        var marker = new google.maps.Marker({
            position: parqueLocation,
            map: map
        });
    } else {
        console.error("Latitud y Longitud no están definidas.");
    }
}

enlaceArboles.addEventListener('click', function() {
    window.location.href = 'arboles.php';
});

enlaceParques.addEventListener('click', function() {
    window.location.href = 'parques.php';
});


