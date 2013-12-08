$(function() {
  "use strict";

  var Birthday = function (data) {
    var self = this;
    var id = data.id;
    var name = data.name;
    var hint = data.hint;
    var dateParts = data.date.split("-");
    var date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
    var today = new Date();
    var age = today.getFullYear() - date.getFullYear();
    var m = today.getMonth() - date.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < date.getDate())) {
      age--;
    }
    var diff = (new Date(today.getFullYear(), dateParts[1] - 1, dateParts[2])).getTime() - today.getTime();

    var title = data.date;
    if (hint) title += " > " + hint;
    var div = $('<div/>').addClass('birthday').attr('title', title)
    .text(data.name + " (" + age + "/" + (age + 1) +")");
    $('#date-' + parseInt(dateParts[1], 10) + '-' + parseInt(dateParts[2], 10)).append(div);

    div.click(function () {
      var result = confirm("Wirklich den Geburtstag von " + name + " entfernen?");
      if (result) {
        destroyBirthday(id, function () {
          reloadBirthdays();
        });
      }
      return false;
    });

    this.delete = function () {
      div.remove();
    };
  };


  function validDate(m, d) {
    var text = "2000-" + m + "-" + d;
    var date = Date.parse(text);

    if (isNaN(date)) {
        return false;
    }

    var comp = text.split('-');

    if (comp.length !== 3) {
        return false;
    }

    var y = parseInt(comp[0], 10);
    var m = parseInt(comp[1], 10);
    var d = parseInt(comp[2], 10);
    var date = new Date(y, m - 1, d);
    return (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d);
  }

  function loadBirthdays(success) {
    api('birthdays', success, alert)    
  }

  function destroyBirthday(id, success) {
    api('birthday/' + id, success, alert, { id: id });
  }

  var lastBirthdays = [];
  function deleteLastBirthdays() {
    for (var i = 0; i < lastBirthdays.length; i++) {
      lastBirthdays[i].delete();
    };
  }

  function reloadBirthdays() {
    loadBirthdays(function (data) {
      deleteLastBirthdays();

      lastBirthdays = [];

      for (var i = 0; i < data.length; i++) {
        lastBirthdays.push(new Birthday(data[i]));
      };
    });
  }

  function api(url, success, failure, post) {
    var handle = function (result) {
      if (!result.data) result = result.responseJSON;
      if (result.status === 200) success(result.data);
      else failure(result.message);
    };
    if (post) {
      $.post('api/' + url, post, 'json').always(handle);
    } else {
      $.get('api/' + url, 'json').always(handle);
    }
  }

  function createBirthday(name, hint, date, success, failure) {
    api('birthday', success, failure, {
      name: name,
      hint: hint,
      date: date
    });
  }

  var tr, td;
  var table = $('<table/>');
  var monthNames = [ "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember" ];

  tr = $('<tr/>');
  for (var i = 0; i < 12; i++) {
    tr.append('<th>' + monthNames[i] + '</th>');
  };
  table.append(tr);

  for (var d = 1; d < 32; d++) {
    tr = $('<tr/>');
    for (var m = 1; m < 13; m++) {
      td = $('<td/>').attr('id', 'date-' + m + '-' + d);
      if (validDate(m, d)) {
        td.addClass('date').append('<h3>' + d + '</h3>');
        td.data('m', m);
        td.data('d', d);
      }
      tr.append(td);
    };
    table.append(tr);
  };
  $('#calendar').append(table);

  $('.date').click(function () {
    var td = $(this);
    $( "#datepicker" ).val('2000-' + td.data('m') + '-' + td.data('d'));
    $( "#dialog-form" ).dialog( "open" );
  });

  $( "#dialog-form" ).dialog({
    autoOpen: false,
    height: 300,
    width: 350,
    modal: true,
    buttons: {
      "Create": function() {
        var name = $('#name').val();
        var hint = $('#hint').val();
        var date = $('#datepicker').val();

        createBirthday(name, hint, date, function () {
          $( "#dialog-form" ).dialog( "close" );
          reloadBirthdays();
        }, function (message) {
          $('#create-error').text(message).show();
        });

      },
      Cancel: function() {
        $( "#dialog-form" ).dialog( "close" );
      }
    },
    close: function() {
      $('#create-error').hide();
    }
  });

  $( "#datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    yearRange: '1900:'+ (new Date).getFullYear()
  });

  reloadBirthdays();
});
