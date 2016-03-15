<!DOCTYPE html>
<html>
<head>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAGxnewKa0500ts2ZIzkQtmK8B6bhsbQ6M"></script>
<script>

var customIcons = {
      Restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
      },
      Auditorium: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      },
      "Municipal Hall": {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_green.png'
      },
      Inn: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_white.png'
      },
      Mall: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_yellow.png'
      },
      Bank: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_grey.png'
      },
      Resort: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_black.png'
      },
      "Amusement Park": {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_orange.png'
      }
    };

function load() {
	var myUPLB=new google.maps.LatLng(14.167525, 121.243368);
	var map = new google.maps.Map(document.getElementById("googleMap"), {
        center: myUPLB,
        zoom: 30,
        mapTypeId: 'roadmap'
    });    

    var infoWindow = new google.maps.InfoWindow;
    var coordinates =[];
    var center;
    // Change this depending on the name of your PHP file
    downloadUrl("phpsqlajax_genxml3.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
        	var name = markers[i].getAttribute("name");
          	var address = markers[i].getAttribute("address");
          	var type = markers[i].getAttribute("type");
          	var point = new google.maps.LatLng(
           	   parseFloat(markers[i].getAttribute("lat")),
           	   parseFloat(markers[i].getAttribute("lng")));
          	var html = "<b>" + name + "</b> <br/>" + address;
          	var icon = customIcons[type] || {};
        	var marker = new google.maps.Marker({
            	map: map,
            	position: point,
            	icon: icon.icon
    		});
          	if(type=="Mall"){
          		coordinates.push(point);
          	}
          	if(name=="SM City Calamba"){
          		center = point;
          	}
        	bindInfoWindow(marker, map, infoWindow, html);
        }
        var flightPath = new google.maps.Polyline({
		    path: coordinates,
		    geodesic: true,
		    strokeColor: '#FFFF00',
		    strokeOpacity: 1.0,
		    strokeWeight: 2
		});
		flightPath.setMap(map);
		var cityCircle = new google.maps.Circle({
	      strokeColor: '#FFFF00',
	      strokeOpacity: 1.0,
	      strokeWeight: 2,
	      fillColor: '#FFFF00',
	      fillOpacity: 0.35,
	      map: map,
	      center: center,
	      radius: 250
	    });
	});
}


   function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

//google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body onload="load()">
	<div id="googleMap" style="width:1500px;height:1500px;"></div>
</body>
</html>
