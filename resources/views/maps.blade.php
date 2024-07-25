<!DOCTYPE html>
<html>
<head>
  <title>Geolocalización con JavaScript</title>
</head>
<body>
  <button onclick="getLocation()">Obtener ubicación</button>
  <p id="location">Ubicación: </p>

  <script>
    function getLocation() {
      if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(success, error, {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
        });
      } else {
        alert("Geolocation is not supported by your browser");
      }
    }

    function success(position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;
      const altitude = position.coords.altitude !== null ? position.coords.altitude : "No disponible";

      document.getElementById('location').textContent = `Latitud: ${latitude}, Longitud: ${longitude}, Altitud: ${altitude}`;
    }

    function error(err) {
      console.warn(`ERROR(${err.code}): ${err.message}`);
      document.getElementById('location').textContent = "No se pudo obtener la ubicación";
    }
  </script>
</body>
</html>
