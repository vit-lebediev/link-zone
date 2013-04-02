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


    var dialogButtons = {};
    dialogButtons[$("#string-add-bonus").val()] = function() {
        $.post(app_env + "/admin/ajax/manage/user/" + $("#userId").val() + "/bonus", {
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
