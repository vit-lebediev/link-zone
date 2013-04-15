jQuery(function(){
    $("#im_field_email_link").click(function() {
        var thisElement = this;
        $(thisElement).startLoading();
        // TODO: validate field
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
        // TODO: validate field
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
        // TODO: validate field
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
        // TODO: validate field
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

    var dialogButtons = {};
    dialogButtons[$("#string-add-bonus").val()] = function() {
        $.post(LinkZone.app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/bonus", {
            amount: $(this).find("input#um_modal_amount").val(),
            comment: $(this).find("textarea#um_modal_comment").val()
        });
        $(this).dialog("close");
    };
    dialogButtons[$("#string-cancel").val()] = function() {
        $(this).dialog("close");
   };

    $("#dialog-add-bonus").dialog({
        resizable: false,
        height: 300,
        width: 400,
        modal: true,
        autoOpen: false,
        buttons: dialogButtons
    })

    $("#im_add_bonus_link").click(function() {
        $("#dialog-add-bonus").dialog("open");
    });
});
