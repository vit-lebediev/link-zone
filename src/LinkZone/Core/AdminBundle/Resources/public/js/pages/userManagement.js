jQuery(function(){
    $("#im_field_email_link").click(function() {
        var thisElement = this;
        $(thisElement).startLoading();
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/email", {
            email: $("#im_field_email").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });

        return;
    });

    $("#im_field_yadengy_link").click(function() {
        var thisElement = this;
        $(thisElement).startLoading();
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/billing", {
            type: "yadengy",
            value: $("#im_field_yadengy").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });

        return;
    });

    $("#im_field_wmr_link").click(function() {
        var thisElement = this;
        $(thisElement).startLoading();
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/billing", {
            type: "wmr",
            value: $("#im_field_wmr").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });

        return;
    });

    $("#im_field_wmz_link").click(function() {
        var thisElement = this;
        $(thisElement).startLoading();
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/billing", {
            type: "wmz",
            value: $("#im_field_wmz").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });

        return;
    });

    /**
     * Add bonus dialog box
     */
    var bonusDialogButtons = {};
    bonusDialogButtons[$("#string-add-bonus").val()] = function() {
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/bonus", {
            amount: $(this).find("input#um_modal_amount").val(),
            comment: $(this).find("textarea#um_modal_comment").val()
        });
        $(this).dialog("close");
    };
    bonusDialogButtons[$("#string-cancel").val()] = function() {
        $(this).dialog("close");
    };

    $("#dialog-add-bonus").dialog({
        resizable: false,
        height: 300,
        width: 400,
        modal: true,
        autoOpen: false,
        buttons: bonusDialogButtons
    });

    $("#im_add_bonus_link").click(function() {
        $("#dialog-add-bonus").dialog("open");
    });


    /**
     * Change status dialog box
     */
    var statusChangeDialogButtons = {};
    var prevStatus = null;

    statusChangeDialogButtons[$("#string-change-status").val()] = function() {
        var thisElement = $(this);
        $(thisElement).startLoading();
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/status", {
            status: $("select#form_status").find("option:selected").val(),
            reason: $(this).find("textarea#um_modal_status_reason").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
            $(thisElement).dialog("close");
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });
    }

    statusChangeDialogButtons[$("#string-cancel").val()] = function() {
        // return previous status to select box
        $("select#form_status option:selected").removeAttr("selected");
        $("select#form_status option[value=" + prevStatus + "]").attr("selected", "selected");
        $(this).dialog("close");
    };

    $("#dialog-change-status").dialog({
        resizable: false,
        height: 300,
        width: 400,
        modal: true,
        autoOpen: false,
        buttons: statusChangeDialogButtons
    });

    $("select#form_status").click(function() {
        // save prev. status to properly manage cancel operation
        prevStatus = $(this).find("option:selected").val();
    });

    $("select#form_status").change(function() {
        $("#um_modal_status_change_status").html($("#string-status-" + $(this).find("option:selected").val()).val());
        $("#dialog-change-status").dialog("open");
    });

    $("#im_reset_password_link").click(function() {
        var thisElement = this;
        $(thisElement).startLoading();
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/resetPassword")
        .done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });

        return;
    });
});
