const loginButton = document.getElementById('loginbutton');
const createAccount = document.getElementById('createaccount');

loginButton.addEventListener("click",  window.location.href="arbolesmadrid.es/login");
createAccount.addEventListener("click", window.location.href="createAccount.php");

function initMap(latitud, longitud, nombreParque) {
    var myLatLng = {lat: latitud, lng: longitud};

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: myLatLng
    });

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: nombreParque
    });
}
