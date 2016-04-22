
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
