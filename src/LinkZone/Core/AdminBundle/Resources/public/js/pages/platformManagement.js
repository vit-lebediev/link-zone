jQuery(function(){
    /**
     * Change status dialog box
     */
    var statusChangeDialogButtons = {};
    var prevStatusSaved = false; // dirty workaround
    var prevStatus = null;

    statusChangeDialogButtons[$("#string-change-status").val()] = function() {
        var thisElement = $(this);
        $(thisElement).startLoading();
        var form = {};
        form['status'] = $("#form_status").find("option:selected").val();
        $.post(LinkZone.app_env + "/admin/ajax/manage/platform/" + $("#platformId").val() + "/status", {
            form: form,
            reason: $(this).find("#form_change_reason").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
            $(thisElement).dialog("close");
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });
    }

    statusChangeDialogButtons[$("#string-cancel").val()] = function() {
        $(this).dialog("close");
    };

    $("#dialog-change-status").dialog({
        resizable: false,
        height: 350,
        width: 400,
        modal: true,
        autoOpen: false,
        buttons: statusChangeDialogButtons,
        close: function (event, ui) {
            prevStatusSaved = false;
            $("#form_status option:selected").removeProp("selected");
            $("#form_status option[value='" + prevStatus + "']").prop("selected", "selected");
        }
    });

    $("select#form_status").mousedown(function() {
        // save prev. status to properly manage cancel operation
        if (prevStatusSaved) {
            return;
        }
        prevStatus = $(this).find("option:selected").val();
        prevStatusSaved = true;
    }).change(function() {
        prevStatusSaved = false;
        var newStatus = $(this).find("option:selected").val();
        $("#pm_status_to_change").html($("#string-status-" + newStatus).val())
                                 .removeClass()
                                 .addClass("status platform_" + newStatus);
        $("#dialog-change-status").dialog("open");
    });
});
