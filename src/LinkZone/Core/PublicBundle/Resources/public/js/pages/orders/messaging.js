jQuery(function()
{
    var sendMessageDialogButtons = {};

    sendMessageDialogButtons[$("#string-send").val()] = function() {
        var thisElement = this;
        var message = {};
        message['message'] = $("#message_message").val();
        message['senderPlatformId'] = $("#message_senderPlatformId").val();
        message['receiverPlatformId'] = $("#message_receiverPlatformId").val();
        message['_token'] = $("#message__token").val();
        $.post(LinkZone.app_env + "/ajax/messages/send", {
            message: message
        }).done(function(jqXHR, textStatus, errorThrown) {
            $(thisElement).dialog("close");
            // TODO: reload the list of platforms via ajax
            window.location.reload();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            // TODO: add notifications
        });
    }

    sendMessageDialogButtons[$("#string-cancel").val()] = function() {
        $(thisElement).dialog("close");
    }

    $(".orders_write_to_user").click(function () {
        var senderPlatformId = $(this).data("senderPlatform");
        var receiverPlatformId = $(this).data("receiverPlatform");
        $.get(LinkZone.app_env + "/ajax/dialog/send-message", {
            senderPlatformId: senderPlatformId,
            receiverPlatformId: receiverPlatformId
        }, function(data) {
            $("#dialog-sendmessage").html(data);
            $("#dialog-sendmessage").dialog({
                resizable: false,
                height: 250,
                width: 500,
                modal: true,
                autoOpen: false,
                buttons: sendMessageDialogButtons
            });

            $("#dialog-sendmessage").dialog("open");
        });
    });
});
