
window.addEventListener("load", function () {
  document.getElementsByClassName('userList')[0].addEventListener('click', function (event) {
    if (event.target.classList.contains('saveUser')) {
      saveUser(event.target.parentNode);
    } else if (event.target.id === 'buttonNewToken') {
      genToken(event.target);
    } else if (event.target.classList.contains('delUser')) {
      deleteUser(event.target.parentNode);
    } else if (event.target.classList.contains('copyLink')) {
      copyLink(event.target.parentNode);
    }
  });
});

function copyLink(target) {
  var token = target.previousElementSibling.innerHTML;
  var textArea = document.createElement('textarea');
  var page = window.location.protocol + '//' + window.location.hostname + ':' + window.location.port + window.location.pathname;
  textArea.innerHTML = page + '?action=redeem&token=' + token;
  textArea.setAttribute('readonly', '');
  textArea.style.zIndex = '-1';
  document.body.appendChild(textArea);
  textArea.select();
  document.execCommand('copy');
  textArea.remove();
}

function genToken(target) {
  var ajax = new XMLHttpRequest();
  ajax.open('GET', './index.php?module=Admin+Panel&page=new+token', true);
  ajax.onload = function () {
    if (ajax.status !== 200) {
      // Server does not return HTTP 200 (OK) response.
      // Whatever you wanted to do when server responded with another code than 200 (OK)
      return; // return is important because the code below is NOT executed if the response is other than HTTP 200 (OK)
    }
    var dummy = document.createElement('div');
    dummy.innerHTML = this.responseText;
    target.parentNode.insertBefore(dummy.firstElementChild, target);
    console.log(this.responseText);
  };
  ajax.send();
}

function deleteUser(target) {
  var username = target.previousElementSibling.innerHTML;
  var type = target.previousElementSibling.dataset.type;

  if (confirm('Are you sure you want to delete\n' + username)) {
    var data = new FormData();
    data.append(type, username);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', './index.php?module=Admin+Panel&page=delete', true);
    ajax.onload = function () {
      if (ajax.status !== 200) {
        return;
      }
    };
    ajax.send(data);
    target.parentNode.parentNode.remove();
  }
}

function saveUser(target) {
  var username = target.previousElementSibling.innerHTML;
  var type = target.previousElementSibling.dataset.type;
  var adminSelect = target.firstElementChild;
  var permissionRows = adminSelect.nextElementSibling.childNodes;
  adminSelect = adminSelect.firstElementChild;
  var setAdmin = null;
  var arrAllow = [];
  var arrDeny = [];

  if (adminSelect.lastElementChild.defaultSelected !== (adminSelect.value === 'Admin')) {
    setAdmin = (adminSelect.value === 'Admin');
    if (adminSelect.lastElementChild.defaultSelected) {
      adminSelect.lastElementChild.removeAttribute('selected');
    } else {
      adminSelect.lastElementChild.defaultSelected = true;
    }
  }

  for (var singleRow of permissionRows) {
    var rowChildren = singleRow.childNodes;
    var checkbox = rowChildren[0].firstElementChild;
    if (checkbox.checked !== checkbox.defaultChecked) {
      checkbox.defaultChecked = checkbox.checked;
      var permission = rowChildren[1].innerHTML;
      console.log(permission);
      if (checkbox.checked) {
        arrAllow.push(permission);
      } else {
        arrDeny.push(permission);
      }
    }
  }

  if (arrAllow.length > 0 || arrDeny.length > 0 || setAdmin !== null) {
    var data = new FormData();
    data.append(type, username);
    if (setAdmin !== null) {
      data.append('admin', setAdmin);
    }
    if (arrAllow.length > 0) {
      data.append('allow', arrAllow.join('_'));
    }
    if (arrDeny.length > 0) {
      data.append('deny', arrDeny.join('_'));
    }
    var ajax = new XMLHttpRequest();
    ajax.open('POST', './index.php?module=Admin+Panel&page=update', true);
    ajax.onload = function () {
      if (ajax.status !== 200) {
        return;
      }
    };
    ajax.send(data);
  }
}

