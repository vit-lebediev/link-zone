jQuery(function(){
    /**
     * Add platform dialog box
     */
    var platformDialogButtons = {};
    platformDialogButtons[$("#string-add-platform").val()] = function() {
        var thisElement = this;
        $(thisElement).startLoading();
        var platform = {};
        platform['url'] = $("#platform_url").val();
        platform['topic'] = $("#platform_topic").val();
        platform['description'] = $("#platform_description").val();
        $.post(LinkZone.app_env + "/ajax/platform", {
            platform: platform
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
            $(thisElement).dialog("close");
            // TODO: reload the list of platforms via ajax
            window.location.reload();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });
    }

    platformDialogButtons[$("#string-cancel").val()] = function() {
        $(this).dialog("close");
    }

    $("#dialog-add-platform").dialog({
        resizable: false,
        height: 300,
        width: 400,
        modal: true,
        autoOpen: false,
        buttons: platformDialogButtons
    });

    $("#platforms_buttons_add").click(function() {
        $("#dialog-add-platform").dialog("open");
    });

});
