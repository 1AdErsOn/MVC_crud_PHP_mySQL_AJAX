<?php
include('includes/header.php');
?>

<script>
// Update the users data list
function getUsers(){
    $.ajax({
        type: 'POST',
        url: 'ajax/ajax.php',
        data: 'action_type=view',
        success:function(html){
            $('#userData').html(html);
        }
    });
}

// Send CRUD requests to the server-side script
function userAction(type, id){
    id = (typeof id == "undefined")?'':id;
    var userData = '', frmElement = '';
    if(type == 'add'){
        frmElement = $("#modalUserAddEdit");
        userData = frmElement.find('form').serialize()+'&action_type='+type+'&id='+id;
    }else if (type == 'edit'){
        frmElement = $("#modalUserAddEdit");
        userData = frmElement.find('form').serialize()+'&action_type='+type;
    }else{
        frmElement = $(".row");
        userData = 'action_type='+type+'&id='+id;
    }
    frmElement.find('.statusMsg').html('');
    alert(userData);
    $.ajax({
        type: 'POST',
        url: 'ajax/ajax.php',
        dataType: 'JSON',
        data: userData,
        beforeSend: function(){
            frmElement.find('form').css("opacity", "0.5");
        },
        success:function(resp){
            frmElement.find('.statusMsg').html(resp.msg);
            if(resp.status == 1){
                if(type == 'add'){
                    frmElement.find('form')[0].reset();
                }
                getUsers();
            }
            frmElement.find('form').css("opacity", "");
        }
    });
}

// Fill the user's data in the edit form
function editUser(id){
    $.ajax({
        type: 'POST',
        url: 'ajax/ajax.php',
        dataType: 'JSON',
        data: 'action_type=data&id='+id,
        success:function(data){
            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
        }
    });
}

// Actions on modal show and hidden events
$(function(){
    $('#modalUserAddEdit').on('show.bs.modal', function(e){
        var type = $(e.relatedTarget).attr('data-type');
        var userFunc = "userAction('add');";
        if(type == 'edit'){
            userFunc = "userAction('edit');";
            var rowId = $(e.relatedTarget).attr('rowID');
            editUser(rowId);
        }
        $('#userSubmit').attr("onclick", userFunc);
    });
    
    $('#modalUserAddEdit').on('hidden.bs.modal', function(){
        $('#userSubmit').attr("onclick", "");
        $(this).find('form')[0].reset();
        $(this).find('.statusMsg').html('');
    });
});
</script>

<?php
if(isset($_GET["pagina"])){
    if($_GET["pagina"] == "admin"){
        include "paginas/".$_GET["pagina"].".php";
    }else{
        include "paginas/404.php";
    }
}else{
    include "paginas/admin.php";
}

include('includes/footer.php');
?>