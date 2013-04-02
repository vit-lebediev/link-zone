(function($){
    var app_env = "/app_dev.php";

    var iconLoadingIdAndClass = "icon_loading";
    var iconCrossIdAndClass = "icon_cross";
    var iconTickIdAndClass = "icon_tick";

    var instance = this;

    $("#im_field_email_link").click(function() {
        console.log("Click user email");
        var clickThis = this;
        instance.startLoading(clickThis);
        // TODO: validate field
        $.post(app_env + "/admin/ajax/manage/user/5/email", {
            email: $("#im_field_email").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            instance.successLoading(clickThis);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            instance.failLoading(clickThis);
        });

        return;
    });

    this.startLoading = function (that)
    {
        var element = $(that).parent().find("." + iconLoadingIdAndClass);
        if (element.size() == 0) {
            $(that).parent("td").append($("#" + iconLoadingIdAndClass).clone().attr("id", "").addClass(iconLoadingIdAndClass));
            element = $(that).parent().find("." + iconLoadingIdAndClass);
        }
        element.show("fast");
    }

    this.failLoading = function (that)
    {
        var loadingElement = $(that).parent().find("." + iconLoadingIdAndClass);
        if (loadingElement.size() > 0) loadingElement.hide("fast");
        var crossElement = $(that).parent().find("." + iconCrossIdAndClass);
        if (crossElement.size() == 0) {
            $(that).parent("td").append($("#" + iconCrossIdAndClass).clone().attr("id", "").addClass(iconCrossIdAndClass));
            crossElement = $(that).parent().find("." + iconCrossIdAndClass);
        }
        crossElement.show("fast");
        crossElement.fadeOut("slow");
    }

    this.successLoading = function (that)
    {
        var loadingElement = $(that).parent().find("." + iconLoadingIdAndClass);
        if (loadingElement.size() > 0) loadingElement.hide("fast");

        var tickElement = $(that).parent().find("." + iconTickIdAndClass);
        if (tickElement.size() == 0) {
            $(that).parent("td").append($("#" + iconTickIdAndClass).clone().attr("id", "").addClass(iconTickIdAndClass));
            tickElement = $(that).parent().find("." + iconTickIdAndClass);
        }
        tickElement.show("fast");
        tickElement.fadeOut("slow");
    }
})(jQuery);
