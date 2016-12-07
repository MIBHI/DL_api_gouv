function toggleMenu() {
    var found = false;
    var menu = document.getElementById("primary_nav");

    for (var i = 0; i <= menu.classList.length-1; i++) {

        if (menu.classList[i]=="active"){

            found=true;
            break;
        }
    }

    if (found) {

        menu.classList.remove("active");
    } else {

        menu.classList.add("active");
    }
}

function getUrl(){
    return 'http://192.168.1.127:8080/api/DL_api_gouv/api/?cp='+document.getElementById("cp").value+'&type='+document.getElementById("type").value;
}

// Create the XHR object.
function createCORSRequest(method, url) {
    var xhr = new XMLHttpRequest();
    if ("withCredentials" in xhr) {
        // XHR for Chrome/Firefox/Opera/Safari.
        xhr.open(method, url, true);
    } else if (typeof XDomainRequest != "undefined") {
        // XDomainRequest for IE.
        xhr = new XDomainRequest();
        xhr.open(method, url);
    } else {
        // CORS not supported.
        xhr = null;
    }
    return xhr;
}

// Make the actual CORS request.
function makeCorsRequest() {

    //If input length is > 5 & select is not empty
    if ( document.getElementById('cp').value.length == 5 && document.getElementById("type").value.length != 0 && document.getElementById("type").value != 'Type'){

        //Clean data
        document.getElementById('dataDisplay').innerHTML = '';

        //Display loader and hide data
        document.getElementById('dataDisplay').style.display = "block";

        //Display loader
        document.getElementById('dataDisplay').setAttribute("class", "loader");

        // This is a sample server that supports CORS.
        var xhr = createCORSRequest('GET', getUrl());

        if (!xhr) {
            alert('CORS not supported');
            return;
        }

        // Response handlers.
        xhr.onload = function() {

            //Array objet of data
            var data = JSON.parse(xhr.responseText);

            //Display data if status if ok
            if (data.status === 'ok'){
                for (var i = 0; i < data.results.length; i++) {
                    document.getElementById('dataDisplay').innerHTML += '<strong><span style="color: #ec971f" class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> </strong> '+data.results[i].addr+'<br>';
                    document.getElementById('dataDisplay').innerHTML += '<strong><span style="color: greenyellow" class="glyphicon glyphicon-usd" aria-hidden="true"></span> </strong>'+data.results[i].price+'<br><br>';
                }
            }else{
                //TODO ERRO MESSAGE
            }

            //Hide loader and display data
            document.getElementById('dataDisplay').setAttribute("class", "");
            //Display div for result
            document.getElementById('dataDisplay').style.display = "block";

        };

        xhr.onerror = function() {
            //Hide loader
            document.getElementById('dataDisplay').setAttribute("class", "");
            //Display error message
            document.getElementById('dataDisplay').innerHTML = "Attention ! Vous n'etes plus connecté à internet, Veuillez reactualiser la page pour acceder aux données" ;
        };

        xhr.send();
    }else {
        //Hide dataDisplay
        document.getElementById('dataDisplay').style.display = "none";
    }
}