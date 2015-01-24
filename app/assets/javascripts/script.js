var loaded = 0
var available = true

function editBooking()
{
  //modal
}
function deleteBooking()
{
  
}
function nonDefaulBooking()
{
  
}
function customBooking()
{
  
}
function book()
{
  
}

function toggleBookButton()
{
  
}

$( document ).ready(function() 
{
    loadMore();
});

function loadMore()
{
  if (available){
  var btn = $('#loadMoreButton')
  $('#spinner').removeClass("invisible")
  $.ajax({
    url:'meals/'+loaded++,
    dataType:"text"})
    .done(function(data)
    {
      if (data != "end")
      {
        newStuff = $.parseHTML(data)
        newId = $(newStuff).attr("id")
        newName = "Week "+newId.slice(4)
        newNav = "<li><a class='navItem' href=\"javascript:scroll('"+newId+"')\">"+newName+"</a></li>"
        $(newNav).insertBefore('#loadMoreButton')
        $('#container').append(newStuff)
        if (loaded < 2)
          loadMore()
        else if (loaded > 2)
          scroll(newId)
      }
      else
      {
        $(btn).remove()
        available = false
      }
    }).always(function()
    {
        $('#spinner').addClass("invisible")
        $('.places').click(showList)
    })
  }
}

function scroll(aid)
{
    var aTag = $('#'+aid);
    $('html,body').animate({scrollTop: aTag.offset().top-40},'slow');
}

function showList(e) 
{
  id = $(e.currentTarget).attr('id').slice(1)
  console.log(id)
  $('table').addClass('hidden')
  $('#noBookings').addClass('hidden')
  $('#bookingsFail').addClass('hidden')
  $.ajax({
      url:'meal/'+id+'/list',
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

function setVeggie(newValue)
{
  $.ajax({
      url:'me/vegetarian',
      type:"POST",
      data:{"vegetarian":newValue},
      dataType:"json"})
    .done(function(data)
    {
      $('#veggie').children().removeClass("active")
      if(data)
        $("#veggie").children().first().addClass("active")
      else
        $("#veggie").children().last().addClass("active")
    })
}