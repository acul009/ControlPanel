function loadConsole() {
  $('#serverConsoleDisplay').load('?module=Lucas&page=console', '', function() {
    var status = $('#serverConsoleDisplay').html().endsWith('Server Offline') ? 'Offline' : 'Online';
    $('#statusSpan').html(status)
  });
}

function initConsole() {
  loadConsole();
  setInterval(loadConsole, 1000);
  $('#consoleSendButton').on('click', sendCommand)
  $(document).keypress(function(e){
    var keycode = e.keyCode || e.which;
    if(keycode == 13) {
        sendCommand();
    }
  })
}

function sendCommand() {
  var url = '?' + $.param({
    module: 'Lucas',
    page: 'command',
    command: $('#consoleInput').val()
  });
  command: $('#consoleInput').val('');
  $.ajax({
    url: url,
    method: 'GET'
  });
}

$(document).ready(initConsole);