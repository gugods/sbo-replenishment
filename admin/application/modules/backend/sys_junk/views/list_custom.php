<style type="text/css">
    #item-sys_action,.btn-publish-all,.glyphicon-globe {
        display: none;
    }
</style>
<script>
function load_on_success(res)
{
	$("#formList table tbody tr").each(function(index){
		if(res[index].reader=="Unread") {
			$(this).find("td div").css({"font-weight":"bold","color":"#2a3f54"});
		}

        var obj = $(this).find("td:eq(1) a");
        $(obj).removeClass("btn-delete glyphicon glyphicon-globe").addClass("fa fa-refresh btn-restore").attr({'title':'Move to inbox'});
	});

    $('.btn-restore').tooltip({container: 'body'});
    $('.btn-restore').tooltip('show');
    $('.btn-restore').tooltip('hide');
}

$(function(){
    $("#item-sys_action .btn-publish-all").remove();
    $('.btn-delete-all').parents('.btn-group').prepend('<button type="button" class="btn btn-xs btn-dark btn-restore-all"><span class="fa fa-refresh"></span> Restore</button>');
    $('.btn-restore-all').attr({'title':'Move to inbox'});
    $('.btn-restore-all').tooltip({container: 'body'});
    $('.btn-restore-all').tooltip('show');
    $('.btn-restore-all').tooltip('hide');

    dialog_restore();
    alert_restore();
    alert_restore_all();
})

function alert_restore() {
    var obj = {};

    obj.title = "Move to inbox ?";
    obj.id = "alert-restore";
    obj.button = [];
    obj.button.push({
        "label": "OK",
        "func": "send_action('cmdPublishToList');",
        "Class": "btn-dark"
    });
    dialogCreate(obj);

    $(document).delegate('.btn-restore', 'click', function() {
        $("input[name^=id]").prop("checked", false);
        var rel = $(this).attr("rel");
        $('#alert-restore').modal('show');
        $("input[name^=id][value=" + rel + "]").prop("checked", true);
    });
}

function dialog_restore() {
    $(".btn-restore-all").click(function() {
        if ($(".check-all").is(":checked")) {
            $('#alert-restore-all').modal('show');
        } else {
            $('#alert-box .modal-title').html("Please select at least 1 item.");
            $('#alert-box').modal('show');
        }
    });
}

function alert_restore_all() {
    var obj = {};

    obj.title = "Move to inbox ?";
    obj.id = "alert-restore-all";
    obj.button = [];
    obj.button.push({
        "label": "OK",
        "func": "send_action('cmdPublishToList');",
        "Class": "btn-dark"
    });
    dialogCreate(obj);
}

</script>