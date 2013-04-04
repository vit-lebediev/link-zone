(function($) {
    LinkZone = {} // global LinkZone object

    LinkZone.app_env = "/app_dev.php"; // prefix for dev app environment URLs
//    LinkZone.app_env = "";

    LinkZone.iconLoadingIdAndClass = "icon_loading";
    LinkZone.iconCrossIdAndClass = "icon_cross";
    LinkZone.iconTickIdAndClass = "icon_tick";

    console.log("FROM BASE");

    LinkZone.from_base = function() {
        console.log("FUNCTION FROM BASE");
    }
})( jQuery );
