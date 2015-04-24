 /*
 * Send ajax query to add event
 *
 * @return: true or array of errors
 */
function addEvent() {
    $.ajax({
        url: '/~user2/PHP/booker/Event/add/',
        method: 'POST',
        data: $("#modal").serialize()
    }).then(function(data){
        var objJ = JSON.parse(data);
        if(objJ[0] == true) {
            $('#myModal').modal('hide');
            setTimeout('window.location.href="/~user2/PHP/booker/Calendar/index"',500);
        }
        else
        {
            $('#wrongdata').attr('class','bg-danger');
            $('#wrongdataerror').html(objJ['ERROR_DATA']).css('color','red');
        }
    })
}

  /*
  * Send ajax query to delete event
  *
  * @return: true or array of errors
  */
function deleteEvent(value, doit)
{
    $.ajax({
        url: '/~user2/PHP/booker/Event/update/id/'+value+'/do/'+doit,
        method: 'POST',
        data: $("#details").serialize()
    }).then(function(data){
        var objJ = JSON.parse(data);
        if(objJ[0] == true) {
            window.onunload = function(){
                if (window.opener){
                    window.opener.location.href = window.opener.location.href;
                }
            };
            window.close();
        }
        else
        {
            $('#warningupdate').html(objJ['ERROR_DATA']);
            window.onunload = function(){
                if (window.opener){
                    window.opener.location.href = window.opener.location.href;
                }
            };
            setTimeout('window.close()',2000);
        }
    })
}

  /*
  * Send ajax query to update event
  *
  * @return: true or array of errors
  */
function updateEvent(value, doit)
{
    $.ajax({
        url: '/~user2/PHP/booker/Event/update/id/'+value+'/do/'+doit,
        method: 'POST',
        data: $("#details").serialize()
    }).then(function(data){
        var objJ = JSON.parse(data);
        if(objJ[0] == true) {
            window.onunload = function(){
                if (window.opener){
                    window.opener.location.href = window.opener.location.href;
                }
            };
            window.close();
        }
        else
        {
            $('#warningupdate').html(objJ['ERROR_DATA']);
            window.onunload = function(){
                if (window.opener){
                    window.opener.location.href = window.opener.location.href;
                }
            };
            setTimeout('window.close()',2000);
        }
    })
}