<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

        data 1: <input id="data1"/><br/>
        data 2: <input id="data2"/><br/>
        <input id="button" type="button" value="Lähetä"/>

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

    </body>
</html>
