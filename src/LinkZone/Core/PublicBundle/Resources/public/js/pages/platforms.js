jQuery(function(){
    $("#platforms_buttons_add").click(function() {
        $.get(LinkZone.app_env + "/ajax/platforms/dialog", function(data) {
            $("#dialog-add-platform").html(data);

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
               $.post(LinkZone.app_env + "/ajax/platforms", {
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
               width: 500,
               modal: true,
               autoOpen: false,
               buttons: platformDialogButtons
           });

            $("#dialog-add-platform").dialog("open");
        });
    });

    $(".platforms_edit_link").click(function () {
        var platformId = $(this).data("platformId");

        $.get(LinkZone.app_env + "/ajax/platforms/dialog", { platform_id: platformId }, function(data) {
            $("#dialog-add-platform").html(data);

            /**
            * Add platform dialog box
            */
           var platformDialogButtons = {};
           platformDialogButtons[$("#string-edit-platform").val()] = function() {
               var thisElement = this;
               $(thisElement).startLoading();
               var platform = {};
               platform['url'] = $("#platform_url").val();
               platform['topic'] = $("#platform_topic").val();
               platform['description'] = $("#platform_description").val();
               $.post(LinkZone.app_env + "/ajax/platforms/" + platformId, {
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
               width: 500,
               modal: true,
               autoOpen: false,
               buttons: platformDialogButtons
           });

            $("#dialog-add-platform").dialog("open");
        });
    });
});
