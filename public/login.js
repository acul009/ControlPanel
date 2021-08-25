/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function login() {
    document.getElementById("inputHash").value = SHA512(document.getElementById("inputPassword").value);
    document.getElementById("formUserHash").submit();
}

function register() {
    var pass = document.getElementById("inputPassword").value;
    var repeat = document.getElementById("confirmPassword").value;
    if (pass === repeat) {
        login();
    } else {
        alert('Both passwords fields need to be the same!');
    }
}

window.onload = function () {
    document.getElementById("buttonSubmit").addEventListener("click", function (event) {
        var type = event.target.dataset.type;
        if (type === 'login') {
            login();
        } else if (type === 'register') {
            register();
        }
    });
    document.getElementById("inputPassword").addEventListener("keyup", function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            login();
        }
    });
};