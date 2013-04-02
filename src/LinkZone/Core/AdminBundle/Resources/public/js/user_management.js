jQuery(function(){
    var app_env = "/app_dev.php";

    var iconLoadingIdAndClass = "icon_loading";
    var iconCrossIdAndClass = "icon_cross";
    var iconTickIdAndClass = "icon_tick";

    var instance = this;

    $("#im_field_email_link").click(function() {
        var clickThis = this;
        instance.startLoading(clickThis);
        // TODO: validate field
        $.post(app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/email", {
            email: $("#im_field_email").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            instance.successLoading(clickThis);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            instance.failLoading(clickThis);
        });

        return;
    });

    $("#im_field_yadengy_link").click(function() {
        var clickThis = this;
        instance.startLoading(clickThis);
        // TODO: validate field
        $.post(app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/billing", {
            type: "yadengy",
            value: $("#im_field_yadengy").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            instance.successLoading(clickThis);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            instance.failLoading(clickThis);
        });

        return;
    });

    $("#im_field_wmr_link").click(function() {
        var clickThis = this;
        instance.startLoading(clickThis);
        // TODO: validate field
        $.post(app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/billing", {
            type: "wmr",
            value: $("#im_field_wmr").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            instance.successLoading(clickThis);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            instance.failLoading(clickThis);
        });

        return;
    });

    $("#im_field_wmz_link").click(function() {
        var clickThis = this;
        instance.startLoading(clickThis);
        // TODO: validate field
        $.post(app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/billing", {
            type: "wmz",
            value: $("#im_field_wmz").val()
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
        element.fadeIn(400, "linear");
    }

    this.failLoading = function (that)
    {
        var loadingElement = $(that).parent().find("." + iconLoadingIdAndClass);
        if (loadingElement.size() > 0) loadingElement.fadeOut(400, "linear");
        var crossElement = $(that).parent().find("." + iconCrossIdAndClass);
        if (crossElement.size() == 0) {
            $(that).parent("td").append($("#" + iconCrossIdAndClass).clone().attr("id", "").addClass(iconCrossIdAndClass));
            crossElement = $(that).parent().find("." + iconCrossIdAndClass);
        }
        crossElement.fadeIn(400, "linear");
        crossElement.fadeOut(1600, "linear");
    }

    this.successLoading = function (that)
    {
        var loadingElement = $(that).parent().find("." + iconLoadingIdAndClass);
        var timeout = 0;
        if (loadingElement.size() > 0) {
            loadingElement.fadeOut(400, "linear");
            timeout = 500;
        }

        setTimeout(function() {
            var tickElement = $(that).parent().find("." + iconTickIdAndClass);
            if (tickElement.size() == 0) {
                $(that).parent("td").append($("#" + iconTickIdAndClass).clone().attr("id", "").addClass(iconTickIdAndClass));
                tickElement = $(that).parent().find("." + iconTickIdAndClass);
            }
            tickElement.fadeIn(400, "linear");
            tickElement.fadeOut(1600, "linear");
        }, timeout);
    }
});
