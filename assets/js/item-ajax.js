var page = 1;
var current_page = 1;
var total_page = 0;
var is_ajax_fire = 0;
manageData();

/* manage data list */
function manageData() {
   $.ajax({
      dataType: 'json',
      url: url + 'get_items',
      data: {page:page}
    }).done(function(data){
       total_page = (data.total / 5);
       console.log(total_page);
       current_page = page;
       if(total_page > 1){
        $('#pagination').twbsPagination({
            totalPages: total_page,
            visiblePages: current_page,
            onPageClick: function (event, pageL) {
                page = pageL;
                if(is_ajax_fire != 0){
                   getPageData();
                }
            }
        });
       }
        manageRow(data.data,data.active_users);
        is_ajax_fire = 1;
   });
}


/* Get Page Data*/
function getPageData() {
    $.ajax({
       dataType: 'json',
       url: + 'get_items',
       data: {page:page}
	}).done(function(data){
       manageRow(data.data);
    });
}

/* Add new Item table row */
function manageRow(data,data1) {
    var	rows = '';
    $.each( data, function( key, value ) {
        rows = rows + '<tr>';
        rows = rows + '<td><input type="checkbox" class="sub_chk" data-id="'+value.id+'"></td>';
        rows = rows + '<td>'+value.title+'</td>';
        rows = rows + '<td>'+value.description+'</td>';
        rows = rows + '<td data-id="'+value.id+'">';
        rows = rows + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Edit</button> ';
        rows = rows + '<button class="btn btn-danger remove-item">Delete</button>';
        rows = rows + '</td>';
        rows = rows + '</tr>';
    });
    $("tbody").html(rows);

    // var rows1 = '';
    // $.each( data1, function( key, value ) {
    //     rows1 = rows1 + '<button type="button" class="btn crud-submit btn-success" id = '+value.id+'>Chat</button>';
    //     rows1 = rows1 + '<br>';
    // });
    // $("#chat_box").html(rows1);
}

$(document).ready(function () {
 
        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });
 
        $('.delete_all').on('click', function(e) {
 
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  
 
            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  
 
                var check = confirm("Are you sure you want to delete this row?");  
                if(check == true){  
                    var join_selected_values = allVals.join(","); 
                    $.ajax({
                        url: 'deleteAll',
                        type: 'POST',
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                          console.log(data);
                          $(".sub_chk:checked").each(function() {  
                              $(this).parents("tr").remove();
                          });
                          alert("Item Deleted successfully.");
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                    manageData();
                }  
            }  
        });
    });



/* Create new Item */
$(".crud-submit").click(function(e){
    e.preventDefault();
    var form_action = $("#create-item").find("form").attr("action");
    var title = $("#create-item").find("input[name='title']").val();
    var description = $("#create-item").find("textarea[name='description']").val();
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{title:title, description:description}
    }).done(function(data){
        $(".modal").modal('hide');
        if(data.code == 1){
            toastr.success(data.msg, 'Success Alert', {timeOut: 1000});    
            manageData();
        }else{
            toastr.error(data.msg, 'Fail Alert', {timeOut: 1000});    
        }
    });
});


/* Remove Item */
$("body").on("click",".remove-item",function(e){
    e.preventDefault();
    var id = $(this).parent("td").data('id');
    var c_obj = $(this).parents("tr");
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: url + 'delete',
        data:{id:id}
    }).done(function(data){
        c_obj.remove();
        if(data.code == 1){
            toastr.success(data.msg, 'Success Alert', {timeOut: 1000});    
            manageData();
        }else{
            toastr.error(data.msg, 'Fail Alert', {timeOut: 1000});    
        }

    });
});

/* Edit Item */
$("body").on("click",".edit-item",function(e){
    e.preventDefault();
    var id = $(this).parent("td").data('id');
    var title = $(this).parent("td").prev("td").prev("td").text();
    var description = $(this).parent("td").prev("td").text();
    $("#get_id").val(id);
    $("#edit-item").find("input[name='title']").val(title);
    $("#edit-item").find("textarea[name='description']").val(description);
    //$("#edit-item").find("form").attr("action",url + '/update/' + id);
});


/* Updated new Item */
$(".crud-submit-edit").click(function(e){
    e.preventDefault();
    $(".crud-submit-edit").attr("disabled", true);
    var form_action = $("#edit-item").find("form").attr("action");
    var title = $("#edit-item").find("input[name='title']").val();
    var description = $("#edit-item").find("textarea[name='description']").val();
    var id = $("#get_id").val();
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{title:title, description:description,id:id}
    }).done(function(data){
        $(".crud-submit-edit").attr("disabled", false);
        $(".modal").modal('hide');
        if(data.code == 1){
            toastr.success(data.msg, 'Success Alert', {timeOut: 1000});    
            manageData();
        }else{
            toastr.error(data.msg, 'Fail Alert', {timeOut: 1000});    
        }
    });
});

// window.setInterval(function(){
//   manageData();
// }, 5000);


$(document).ready(function () {

      $("#get_chat").hide();  
      $(".send-chat").on("click",function(e){
        $("#get_chat").show();  
        var msg_id = this.id;
        $("#receiver_id").val(msg_id);
        manage_chat_data(msg_id);
    });

     $(".send-msg").on("click",function(e){ 
        var message = $("#msg_body").val();
        alert(message);
        var receiver_id = $("input[name=receiver_id]").val();
        
        e.preventDefault();
        
        $.ajax({
            dataType: 'json',
            type:'POST',
            url: 'add_user_message',
            data:{receiver_id:receiver_id,message:message}
        }).done(function(data){
            if(data.code == 1){
                toastr.success(data.msg, 'Success Alert', {timeOut: 1000});    
                manage_chat_data(receiver_id);
            }else{
                toastr.error(data.msg, 'Fail Alert', {timeOut: 1000});    
            }
        });
});

function get_chat_data(data) {
    var rows = '';
    $.each( data, function( key, value ) {
        rows = rows + '<div class="container">';
        rows = rows + '<p>'+value.message+'</p>';
        rows = rows + '<span class="time-right">11:00</span>';
        rows = rows + '</div>';
    });
    $("#fetch_data").html(rows);
}     

function manage_chat_data(receiver_id) {
   $.ajax({
      dataType: 'json',
      type:'POST',
      url: url + 'get_chat_details',
      data: {receiver_id:receiver_id}
    }).done(function(data){
        get_chat_data(data.data);
   });
}

window.setInterval(function(){
    var receiver_id = $("input[name=receiver_id]").val();
    if(receiver_id != ''){
        manage_chat_data(receiver_id);
    }
}, 5000);


});