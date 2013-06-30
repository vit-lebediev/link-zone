jQuery(function()
{
    $(".send_code_for_user").click(function() {
        var thisElement = this;
        var textField = $(thisElement).closest("td").find(".code_for_user");
        var url;
        if ($(thisElement).data("for") == "receiver") {
            url = LinkZone.app_env + "/ajax/orders/" + $(thisElement).data("orderId") + "/receiver-link-location";
        } else if ($(thisElement).data("for") == "sender") {
            url = LinkZone.app_env + "/ajax/orders/" + $(thisElement).data("orderId") + "/sender-link-location";
        } else {
            console.log("ERROR");
            return;
        }
        $(textField).startLoading();
        $.post(url, {
            linkLocation: $(textField).val()
        }).done(function() {
            $(textField).successLoading();
        }).fail(function() {
            $(textField).failLoading();
        });
    });

    $(".orders_accept_order").click(function() {
        var thisElement = this;
        $.post(LinkZone.app_env + "/ajax/orders/" + $(thisElement).data("orderId") + "/accept-order", {
            accept: true
        }).done(function() {
            window.location.reload();
        }).fail(function() {

        });
    });

    $(".orders_cancel_order").click(function() {
        var thisElement = this;
        $.post(LinkZone.app_env + "/ajax/orders/" + $(thisElement).data("orderId") + "/accept-order", {
            accept: false
        }).done(function() {
            window.location.reload();
        }).fail(function() {

        });
    });
});
