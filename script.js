function showList(id) 
{
  $('table').addClass('hidden')
  $('#noBookings').addClass('hidden')
  $('#bookingsFail').addClass('hidden')
  $.ajax({
      url:'http://robert.bastian.com/meal/list.php?id='+id,
      dataType:"json"})
    .done(function(data)
    {
      $('#listLabel').html(data[0])
      var list = data[1]
      if (list.length > 0)
      {
        $('table').removeClass('hidden'); $('#listTable').empty()
        for (var i = 0; i < list.length; i++)
          $('#listTable').append("<tr"+((list[i][3]==user['user'])?" class='info'":"")
            +"><td>"+list[i][0]
            +"</td><td>"+list[i][1]
            +"</td><td>"+list[i][2]+"</td></tr>")
      }
      else
        $('#noBookings').removeClass('hidden')
    })
    .fail(function(){
        $('#listLabel').html("Error")
        $('#bookingsFail').removeClass('hidden')
    })
    .always(function(){
      $('#listModal').modal()
    })
}

function bookPreset(id)
{

}

function nonDefaulBooking(id)
{

}

function customBooking(id)
{

}

/* Vegetarian toggle in settings */
function updateVeggieButtons()
{
  $('#veggie').children.button('reset')
  if(user['vegetarian'])
    $("#veggie").first.button('toggle')
  else
    $("#veggie").last.button('toggle')
}

function setVeggie()
{
  $('#veggie').button('loading')
  newValue = $("#veggie").find("button.active").prop('value');
  if (newValue != user['vegetarian'])
  {
    $.ajax({
        url:'http://robert.bastian.com/meal/veggie.php?v='+newValue+"&user="+user['user'],
        dataType:"json"})
      .done(function(data)
      {
        if(!data['success'])
          user['vegetarian'] = newValue
      })
      .always(updateVeggieButtons)
  }
}

$('document').ready(function(){
  var user = $.parseJSON(user)
})