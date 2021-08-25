/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.onload = function () {
    document.getElementById('NewServer').addEventListener('click', function (event) {
        var ajax = new XMLHttpRequest();
        ajax.open('GET', '?module=Minecraft&page=NewServer', true);
        ajax.onload = function () {
            if (ajax.status !== 200) {
                // Server does not return HTTP 200 (OK) response.
                // Whatever you wanted to do when server responded with another code than 200 (OK)
                return; // return is important because the code below is NOT executed if the response is other than HTTP 200 (OK)
            }
            // Whatever you wanted to do when server responded with HTTP 200 (OK)
            // I've added a DIV with id of testdiv to show the result there
            var dummy = document.createElement('div');
            dummy.innerHTML = this.responseText;
            document.getElementById('NewBlock').appendChild(dummy.firstElementChild);


        };
        ajax.send();
    });
};

