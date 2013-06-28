jQuery(function()
{
    // init Tags
    $("#platform_tags").tagit({
        fieldName: "platform_tags",
        singleField: true,
        autocomplete: {
            minlength: 2,
            source: LinkZone.app_env + "/ajax/platforms/tags"
        }
    });

    var sendRequestDialogButtons = {};
    sendRequestDialogButtons[$("#string-send-order").val()] = function() {
        var thisElement = this;
        $(thisElement).startLoading();
        var request = {};
        request['senderPlatform'] = $("#send_request_senderPlatform option:selected").val();
        request['senderLink'] = $("#send_request_senderLink").val();
        request['senderLinkText'] = $("#send_request_senderLinkText").val();
        request['receiverPlatformId'] = $("#send_request_receiverPlatformId").val();
        request['_token'] = $("#send_request__token").val();
        $.post(LinkZone.app_env + "/ajax/orders/send-order", {
            send_request: request
        }).done(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
            $(thisElement).dialog("close");
            // TODO: reload the list of platforms via ajax
            window.location.reload();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });
    }

    sendRequestDialogButtons[$("#string-cancel").val()] = function() {
        $(this).dialog("close");
    }

    // send order onclick
    $(".platform_send_request").click(function() {
        var platformId = $(this).data("platformId");
        $.get(LinkZone.app_env + "/ajax/orders/send-order-dialog", { platform_id: platformId }, function(data) {
            $("#dialog-add-platform").html(data);
            $("#dialog-add-platform").dialog({
                resizable: false,
                height: 400,
                width: 500,
                modal: true,
                autoOpen: false,
                buttons: sendRequestDialogButtons
            });

            $("#dialog-add-platform").dialog("open");
        });
    });
});
