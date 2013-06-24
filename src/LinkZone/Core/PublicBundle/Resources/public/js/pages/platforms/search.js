jQuery(function()
{
    // init Tags
    (function()
    {
        $("#platform_tags").tagit({
            fieldName: "platform_tags",
            singleField: true,
            autocomplete: {
                minlength: 2,
                source: LinkZone.app_env + "/ajax/platforms/tags"
            }
        });
    })();
});
