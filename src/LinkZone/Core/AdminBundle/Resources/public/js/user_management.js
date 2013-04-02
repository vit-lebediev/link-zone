(function($){
    var app_env = "/app_dev.php";

    $("#im_field_email_link").click(function() {
        console.log("Click user email");
        // TODO: validate field
        $.post(app_env + "/admin/ajax/manage/user/5/email", {
            email: $("#im_field_email").val()
        }).done(function (jqXHR, textStatus, errorThrown) {
            console.log("success");
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("error");
        });

        return;
    });
})(jQuery);
