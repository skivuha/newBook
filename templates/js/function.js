 /*
 * Event then click recurring 'on' or 'off'
 */
function setRecurringShowHide()
{
    //then recurring on
    $('#recuringon').on('click', function(){
            $('#recurring').css('display', 'block');
            $('#weekly').attr('checked', 'checked');
        }
    );

    //then recurring off
    $('#recuringoff').on('click', function(){
            $('#recurring').css('display', 'none');
            $('#weekly').removeAttr('checked');
            $('#bi-weekly').removeAttr('checked');
            $('#monthly').removeAttr('checked');
        }
    );
}

 /*
 * Set new data selected
 */
function setSelectedData()
{
    $('#dropdownyear, #dropdownmonth').on('click', function(){
        var now = new Date();
        var selectedDay = $('#dropdownday :selected').text();
        var selectedYear = $('#dropdownyear :selected').text();
        var selectedMonth = $('#dropdownmonth :selected').attr('value');
        var currentMonth = now.getMonth();
        var currentYear = now.getFullYear();
        var listDay = '';
        var selectedYearMonth = new Date(selectedYear, selectedMonth, 1);
        var dayInMonth = selectedYearMonth.daysInMonth();
        for(var i = 1; i <= dayInMonth ; i++)
        {
            var weekend = new Date(selectedYear, selectedMonth, i).getDay();
            if( 0 == weekend || 6 == weekend
                || ((currentMonth > selectedMonth
                &&  currentYear >= selectedYear)))
            {
                listDay += '<option disabled value="'+i+'">'+i+'</option>';
            }
            else {
                listDay += '<option value="' + i + '">' + i + '</option>';
            }
        }

        $('#dropdownday').html(listDay);
        $('#dropdownday option[value="'+selectedDay+'"]')
            .attr('selected','selected');
    });
}

  /*
  * Set data format time for modal window
  */
function setValueStartHour()
{
    $('#dropdowntypestart, #dropdownhourstart').on('click', function() {
        var value = $('#dropdownhourstart :selected').attr('value');
        var start = $('#dropdowntypestart :selected').text();
        var hourStart = $('#dropdownhourstart :selected').attr('value');
        var pm = 12;
        if('PM' == start)
        {
            $('#dropdownhourstart option:contains(00)')
                .attr('disabled','disabled');
            if('00' == hourStart)
            {
                $('#dropdownhourstart option:contains(01)')
                    .attr('selected','selected')
            }
            var selectedHour = parseInt($('#dropdownhourstart :selected')
                    .text()) + pm;
            if(24 == selectedHour)
            {
                selectedHour = '12';
            }

            $('#dropdownhourstart :selected').attr('value', selectedHour);
            $('#dropdownhourstart option:contains(12)').removeAttr('disabled');
        }
        if('AM' == start)
        {
            if( 12 <= value )
            {
                var selectedHour = parseInt(hourStart) - pm;
            }
            $('#dropdownhourstart :selected').attr('value', selectedHour);
            $('#dropdownhourstart option[value="00"]').removeAttr('disabled');
            $('#dropdownhourstart option:contains(12)')
                .attr('disabled','disabled');
            if('12' == hourStart)
            {
                $('#dropdownhourstart option:contains(11)')
                    .attr('selected','selected')
            }
        }
    })
}

  /*
  * Set data format time for modal window
  */
function setValueEndHour() {
    $('#dropdowntypeend, #dropdownhourend').on('click', function() {
        var end = $('#dropdowntypeend :selected').text();
        var hourEnd = $('#dropdownhourend :selected').attr('value');
        var value = $('#dropdownhourend :selected').attr('value');
        var pm = 12;
        if ('PM' == end)
        {
            $('#dropdownhourend option:contains(00)')
                .attr('disabled','disabled');
            if('00' == hourEnd)
            {
                $('#dropdownhourend option:contains(01)')
                    .attr('selected','selected')
            }
            var selectedHour = parseInt($('#dropdownhourend :selected')
                    .text()) + pm;
            if(24 == selectedHour)
            {
                selectedHour = '12';
            }
            $('#dropdownhourend :selected').attr('value', selectedHour);
            $('#dropdownhourend option:contains(12)').removeAttr('disabled');
        }
        if('AM' == end)
        {
            if( 12 <= value )
            {
                var selectedHour = parseInt(hourEnd) - pm;
            }
            $('#dropdownhourend :selected').attr('value', selectedHour);
            $('#dropdownhourend option[value="00"]').removeAttr('disabled');
            $('#dropdownhourend option:contains(12)')
                .attr('disabled','disabled');
            if('12' == hourEnd)
            {
                $('#dropdownhourend option:contains(11)')
                    .attr('selected','selected')
            }
        }
    })
}

  /*
  * Set mix duration of recurrent event
  */
function setMaxDuration()
{
    $('#weekly').on('click', function() {
        $('#duration').attr('max', '4');
    });
    $('#bi-weekly').on('click', function() {
        $('#duration').attr('max', '2');
    });
    $('#monthly').on('click', function() {
        $('#duration').attr('max', '1');
    });
}

  /*
  * Get data from cookie
  */
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

 /*
  * Open new window
  */
function setNewWindow()
{
    var height = (screen.height/2)-225;
    var widht = (screen.width/2)-175;
    $('.event').bind('click', function () {
        var target = event.target || event.srcElement;
        var id = $(this).attr('name');
        window.open('/~user2/PHP/booker/Event/edit/id/'+id, 'Details', 'location, width = 350px,' +
        'height = 450px, top = '+height+'px, left = '+widht+'px ').focus();
    });
}

 /*
  * Confirm message
  */
function showConfirmMessageThenDeleteEmployee()
{
    $('.deleteEmp').on('click', function(){
        if(confirm('Are you sure you want to delete this contact?'))
        {
            return true;
        }
        else
        {
            return false;
        }
    });
}