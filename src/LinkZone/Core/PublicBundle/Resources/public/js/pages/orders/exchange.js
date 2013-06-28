jQuery(function()
{
    var viewRequestDialogButtons = {};

    viewRequestDialogButtons[$("#string-deny-order").val()] = function() {
        var thisElement = this;
        $.post(LinkZone.app_env + "/ajax/orders/" + $("#review_request_id").val() + "/deny").done(function(jqXHR, textStatus, errorThrown) {
            $(thisElement).dialog("close");
            window.location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log("Something went wrong");
        });
    }

    viewRequestDialogButtons[$("#string-approve-order").val()] = function() {
        var thisElement = this;
        $(thisElement).startLoading();
        var request = {};
        request['receiverLink'] = $("#review_request_receiverLink").val();
        request['receiverLinkText'] = $("#review_request_receiverLinkText").val();
        request['_token'] = $("#review_request__token").val();
        $.post(LinkZone.app_env + "/ajax/orders/" + $("#review_request_id").val() + "/approve", {
            review_request: request
        }).done(function(jqXHR, textStatus, errorThrown) {
            $(thisElement).successLoading();
            $(thisElement).dialog("close");
            // TODO: reload the list of platforms via ajax
            window.location.reload();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $(thisElement).failLoading();
        });
    }

    $(".orders_review_order").click(function() {
        var orderId = $(this).data("orderId");
        $.get(LinkZone.app_env + "/ajax/dialog/orders/" + orderId + "/review", function(data) {
            $("#dialog-review-order").html(data);
            $("#dialog-review-order").dialog({
                resizable: false,
                height: 400,
                width: 500,
                modal: true,
                autoOpen: false,
                buttons: viewRequestDialogButtons
            });

            $("#dialog-review-order").dialog("open");
        });
    });
});
