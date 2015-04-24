 /*
 * Run then document loaded
 */
$(document).ready(
    function()
    {
        setRecurringShowHide();
        setNewWindow();
        showConfirmMessageThenDeleteEmployee();

        //cleate dropdown list day, month, year for modal window
        var now = new Date();
        var currentYear = now.getFullYear();
        var currentMonth = now.getMonth();
        var currentDay = now.getDate();
        var listYear = '';
        var futureYear;
        for(var i = 0; i <= 5; i++)
        {
            futureYear = new Date(currentYear+i,0,1);
            var year = futureYear.getFullYear();
            listYear += '<option value="'+year+'">'+year+'</option>';
        }
        $('#dropdownyear').html(listYear);

        var lang = getCookie('langanator');

        if('en' == lang)
        {
            var monthName = new Array('January', 'February', 'March',
                'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December');
        }else {
            var monthName = new Array('Январь', 'Февраль', 'Март',
                'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
                'Октябрь', 'Ноябрь', 'Декабрь');
        }
        var listMonth = '';
        for(var i = 0; i <= 11; i++)
        {
            futureYear = new Date(currentYear,0+i,1);
            var month = futureYear.getMonth();
            listMonth += '<option value="'+i+'">'+monthName[i]+'</option>';
        }

        $('#dropdownmonth').html(listMonth);
        $('option[value="'+currentYear+'"]').attr('selected','selected');
        $('option[value="'+currentMonth+'"]').attr('selected','selected');

        var selectedYear = $('#dropdownyear :selected').text();
        var selectedMonth = $('#dropdownmonth :selected').attr('value');

        var listDay = '';
        var selectedYearMonth = new Date(selectedYear, selectedMonth, 1);
        var dayInMonth = selectedYearMonth.daysInMonth();
        for(var i = 1; i <= dayInMonth ; i++)
        {
            var weekend = new Date(selectedYear, selectedMonth, i).getDay();
            if( 0 == weekend || 6 == weekend )
            {
                listDay += '<option disabled value="'+i+'">'+i+'</option>';
            }
            else {
                listDay += '<option value="' + i + '">' + i + '</option>';
            }
        }

        $('#dropdownday').html(listDay);
        $('option[value="'+currentDay+'"]').attr('selected','selected');
        setSelectedData();

        var hourFormat = getCookie('user2_timeFormat');
        var listHour = '';
        if('12h' == hourFormat) {
            setValueStartHour();
            setValueEndHour();
        }
        if('24h' == hourFormat)
        {
            for(var i = 0; i <= 23; i++)
            {
                if(10 > i) {
                    listHour += '<option value="' + '0' + i + '">'
                    + '0' + i + '</option>';
                }else
                {
                    listHour += '<option value="' + i + '">'
                    + i + '</option>';
                }
            }
            $('#dropdowntypestart, #dropdowntypeend')
                .attr('disabled', 'disabled').css('color', '#ccc');
        }
        else
        {
            for(var i = 0; i <= 12; i++)
            {
                if('12h' == hourFormat) {
                    if (10 > i) {
                        listHour += '<option value="' + '0' + i + '">'
                        + '0' + i + '</option>';
                    } else {
                        listHour += '<option value="'
                        + i + '">' + i + '</option>';
                    }
                }
            }
        }
        $('#dropdownhourstart, #dropdownhourend').html(listHour);
        setMaxDuration();
        $('#savemodal').on('click', function(){
            addEvent();
        });
        $('#deletebuttn').on('click', function(){
            var id = $('#deletebuttn').attr('value');
            var name = $('#deletebuttn').attr('name');
            deleteEvent(id, name);
        });
        $('#updatebuttn').on('click', function(){
            var id = $('#updatebuttn').attr('value');
            var name = $('#updatebuttn').attr('name');
            updateEvent(id, name);
        });
    }
);

Date.prototype.daysInMonth = function() {
    return 32 - new Date(this.getFullYear(), this.getMonth(), 32).getDate();
};



