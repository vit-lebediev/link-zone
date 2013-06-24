jQuery(function()
{
    function initTags()
    {
        $("#platform_tags").tagit({
            fieldName: "platform_tags",
            singleField: true,
            autocomplete: {
                minlength: 2,
                source: LinkZone.app_env + "/ajax/platforms/tags"
            }
        });
    }

    function openPlatformDialog(platformDialogButtons)
    {
        $("#dialog-add-platform").dialog({
            resizable: false,
            height: 400,
            width: 500,
            modal: true,
            autoOpen: false,
            buttons: platformDialogButtons
        });

         $("#dialog-add-platform").dialog("open");
    }

    function getPlatformDialogButtons(platformId)
    {
        // set default
        platformId = typeof platformId !== "undefined" ? platformId : false;

        var stringId,
            postUrl;

        if (platformId) {
            stringId = "string-edit-platform";
            postUrl = LinkZone.app_env + "/ajax/platforms/" + platformId;
        } else {
            stringId = "string-add-platform";
            postUrl = LinkZone.app_env + "/ajax/platforms";
        }

        var platformDialogButtons = {};
        platformDialogButtons[$("#" + stringId).val()] = function() {
            var thisElement = this;
            $(thisElement).startLoading();
            var platform = {};
            platform['url'] = $("#platform_url").val();
            platform['topic'] = $("#platform_topic").val();
            platform['description'] = $("#platform_description").val();
            if ($("#platform_hidden").prop("checked")) {
                platform['hidden'] = true;
            }
            platform['tags'] = $("input[name='platform_tags']").val();
            $.post(postUrl, {
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

        return platformDialogButtons;
    }

    $("#platforms_buttons_add").click(function()
    {
        $.get(LinkZone.app_env + "/ajax/platforms/dialog", function(data) {
            $("#dialog-add-platform").html(data);
            openPlatformDialog(getPlatformDialogButtons());
            initTags();
        });
    });

    $(".platforms_edit_link").click(function () {
        var platformId = $(this).data("platformId");
        $.get(LinkZone.app_env + "/ajax/platforms/dialog", { platform_id: platformId }, function(data) {
            $("#dialog-add-platform").html(data);
            openPlatformDialog(getPlatformDialogButtons(platformId));
            initTags();
        });
    });
});
