'use strict';

LinkZone.filter('reverse', function() {
    return function (items) {
        return items.slice().reverse();
    }
});
