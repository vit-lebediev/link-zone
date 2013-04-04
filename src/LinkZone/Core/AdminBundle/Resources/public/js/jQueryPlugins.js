(function($) {
    $.fn.startLoading = function() {
        console.log("BP0 = " + this.prop("tagName"));
        var element = this.parent().find("." + LinkZone.iconLoadingIdAndClass);
        if (element.size() == 0) {
            this.parent().append($("#" + LinkZone.iconLoadingIdAndClass).clone().attr("id", "").addClass(LinkZone.iconLoadingIdAndClass));
            element = this.parent().find("." + LinkZone.iconLoadingIdAndClass);
        }
        element.fadeIn(200);
    };

    $.fn.successLoading = function() {
        var thisElement = this;
        var loadingElement = thisElement.parent().find("." + LinkZone.iconLoadingIdAndClass);
        if (loadingElement.size() > 0) {
            loadingElement.fadeOut(400, function() {
                var tickElement = thisElement.parent().find("." + LinkZone.iconTickIdAndClass);
                if (tickElement.size() == 0) {
                    thisElement.parent().append($("#" + LinkZone.iconTickIdAndClass).clone().attr("id", "").addClass(LinkZone.iconTickIdAndClass));
                    tickElement = thisElement.parent().find("." + LinkZone.iconTickIdAndClass);
                }
                tickElement.fadeIn(400);
                tickElement.fadeOut(1600);
            });
        }
    }

    $.fn.failLoading = function() {
        var thisElement = this;
        var loadingElement = thisElement.parent().find("." + LinkZone.iconLoadingIdAndClass);
        if (loadingElement.size() > 0) {
            loadingElement.fadeOut(400, function() {
                var crossElement = thisElement.parent().find("." + LinkZone.iconCrossIdAndClass);
                if (crossElement.size() == 0) {
                    thisElement.parent().append($("#" + LinkZone.iconCrossIdAndClass).clone().attr("id", "").addClass(LinkZone.iconCrossIdAndClass));
                    crossElement = thisElement.parent().find("." + LinkZone.iconCrossIdAndClass);
                }
                crossElement.fadeIn(400);
                crossElement.fadeOut(1600);
            });
        }
    }
})( jQuery );