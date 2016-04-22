---
layout: default
title: form2xhr
---

# Esimerkki ...

Versioiden koodi löytyy
[GitHubista](https://github.com/timedu/weo/tree/gh-pages/demot/form2xhr).


## Versio 0


### index.php (käyttöliittymä)

Ottaa vastaan käyttäjältä datan ja lähettää sen `POST`-pyynnön mukana osoitteeseen `service.php` käyttäjän klikattua submit-nappia.


~~~
...
    <body>
        <form method="POST" action="service.php">
            data 1: <input name="data1"/><br/>
            data 2: <input name="data2"/><br/>
            <input type="submit" value="Lähetä"/>
        </form>
    </body>
</html>    
~~~


### service.php

Lukee POST-pyynnön välittämän datan ja tulostaa sen NetBeansin Output-ikkunaan sekä ohjaa käsittelyn takaisin käyttöliittymälle.

~~~
$httpBody = file_get_contents('php://input');
debug($httpBody);
header('Location: index.php');

function debug($data) {
    file_put_contents('php://stderr', $data . PHP_EOL);
}
~~~

Jos käyttöliittymän tekstikenttiin syötetään arvot `a` ja `b`, Output -ikkunaan tulostuu `data1=a&data2=b`.


## Versio 1


### index.php

Näyttää ulospäin samalta kuin edellinen versio, mutta lähettää datan käyttäen `XMLHttpRequest`-oliota käyttäjän klikattua sivulla olevaa nappia.


~~~
...
    <body>

        data 1: <input id="data1"/><br/>
        data 2: <input id="data2"/><br/>
        <input id="button" type="button" value="Lähetä"/>

        <script>

            window.onload = function () {
                document.getElementById('button').onclick = sendData;
            };
            
            function sendData() {

                var data =
                        'data1=' + document.getElementById('data1').value +
                        '&' +
                        'data2=' + document.getElementById('data2').value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'service.php');
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send(data);
                
            }

        </script>

    </body>
</html>

~~~

Sivulla ei ole `form`-elementtiä eikä `submit`-nappia, jonka on korvannut "tavallinen" `button`. JavaScript -koodi on sidottu napin `click`-tapahtumaan. 

### service.php

On lähes sama kuin edellinen versio. Ei kuitenkaan sisällä uudelleenohjaavaa lausetta.

~~~
$httpBody = file_get_contents('php://input');
debug($httpBody);

function debug($data) {
    file_put_contents('php://stderr', $data . PHP_EOL);
}
~~~


## Versio 2


### index.php

Lähettää datan "lomake-muodon" sijaan `json`-muodossa. On muuten sama kuin edellinen versio.

~~~
...
        <script>

            window.onload = function () {
                document.getElementById('button').onclick = sendData;
            };
            
            function sendData() {

                var data = {};                
                data.data1 = document.getElementById('data1').value;
                data.data2 = document.getElementById('data2').value;
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'service.php');
                xhr.setRequestHeader("Content-type", "application/json");
                
                xhr.send(JSON.stringify(data));                
            }

        </script>
...
~~~


### service.php

On sama kuin edellinen versio. Jos käyttöliittymän tekstikenttiin syötetään arvot `a` ja `b`, NetBeansin Output -ikkunaan tulostuu nyt `{"data1":"a","data2":"b"}`.


## Versio 3

### index.php

On sama kuin edellinen versio.

### service.php

Muuntaa pyynnön mukana saadun datan merkkijonoilla indeksoiduksi taulukoksi ja tulostaa alkiot erillisinä Netbeansin Output-ikkunaan.

~~~
<?php

$httpBody = file_get_contents('php://input');
$data = json_decode($httpBody, true);

debug($data['data1']);
debug($data['data2']);

function debug($data) {
    file_put_contents('php://stderr', $data . PHP_EOL);
}
~~~

Jos käyttöliittymän tekstikenttiin syötetään arvot `a` ja `b`,  Output -ikkunaan tulostuu nyt `a` ja `b` omille riveilleen. 


## Versio 4


### index.php

Pyynnön lähetyksen lisäksi käsittelee vasteen mukana saadun datan tulostaen sen selaimen konsolille. Jos tekstikenttiin on syötetty saman arvot, konsolille tulostuu `{"tulos":"samat"}` muutoin `{"tulos":"erit"}`.

~~~
...
        <script>

            window.onload = function () {
                document.getElementById('button').onclick = sendData;
            };
            
            function sendData() {

                var data = {};                
                data.data1 = document.getElementById('data1').value;
                data.data2 = document.getElementById('data2').value;
                
                var xhr = new XMLHttpRequest();
                xhr.onload = receiveData;
                xhr.open('POST', 'service.php');
                xhr.setRequestHeader("Content-type", "application/json");
                
                xhr.send(JSON.stringify(data));                
            }
            
            function receiveData() {                
                console.log(this.responseText);
            }
            
        </script>
...
~~~


### service.php

Rakentaa vasteen ja tulostaa sen `json`-muodossa. 

~~~
$httpBody = file_get_contents('php://input');
$input = json_decode($httpBody, true);

$output = [];

if ($input['data1'] == $input['data2']){
    $output['tulos'] = 'samat';
} else {
    $output['tulos'] = 'erit';
}

header('Content-type: application/json');
echo json_encode($output);
~~~


## Versio 5


### index.php

Muuntaa vastaanotetun `json`-muotoisen merkkijonon JavaScript -objektiksi ja  tulostaa konsolille ainoastaan `tulos`-ominaisuuden arvon.

~~~
        <script>

            window.onload = function () {
                document.getElementById('button').onclick = sendData;
            };
            
            function sendData() {

                var data = {};                
                data.data1 = document.getElementById('data1').value;
                data.data2 = document.getElementById('data2').value;
                
                var xhr = new XMLHttpRequest();
                xhr.onload = receiveData;
                xhr.open('POST', 'service.php');
                xhr.setRequestHeader("Content-type", "application/json");
                
                xhr.send(JSON.stringify(data));                
            }
            
            function receiveData() {
                var response = JSON.parse(this.responseText)                
                console.log(response.tulos);
            }
            
        </script>
~~~

Konsolille tulostuu joko `samat` tai `erit` riippuen tekstikenttiin syötetyistä arvoista.


### service.php

On sama kuin edellinen versio.


## Versio 6

### index.php

JavaSript on erillisessä tiedostossa. Käyttöliittymässä on `div`-elementti tuloksen esittämistä varten.

~~~
...
    <body>

        data 1: <input id="data1"/><br/>
        data 2: <input id="data2"/><br/>
        <input id="button" type="button" value="Lähetä"/>
        
        <div id="tulos"></div>

        <script src="index.js"></script>

    </body>
</html>

~~~

### index.js

Esittää tuloksen sivulla konsolitulostuksen sijaan.

~~~
window.onload = function () {
    document.getElementById('button').onclick = sendData;
};

function sendData() {

    var data = {};
    data.data1 = document.getElementById('data1').value;
    data.data2 = document.getElementById('data2').value;

    var xhr = new XMLHttpRequest();
    xhr.onload = receiveData;
    xhr.open('POST', 'service.php');
    xhr.setRequestHeader("Content-type", "application/json");

    xhr.send(JSON.stringify(data));
}

function receiveData() {

    var response = JSON.parse(this.responseText);

    var h3 = document.createElement('h3');
    h3.textContent = response.tulos;

    var div = document.getElementById('tulos');
    div.innerHTML = '';
    div.appendChild(h3);

}

~~~

Muodostaa `h3`-elementin ja asettaa sen `div`-elementin sisällöksi.


### service.php

On sama kuin edellisessä versiossa.


