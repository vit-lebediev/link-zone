'use strict';

function PlatformsSearchController($scope, $dialog, Platform)
{
    $scope.platforms = Platform.search();

    // details:
    // @link http://ivaynberg.github.io/select2/
    // @link https://github.com/angular-ui/ui-select2
    $scope.select2Options = {
        multiple: true,
        placeholder: "Input tags",
        minimumInputLength: 2,
        ajax: {
            url: $scope.routePrefix + "/ajax/platforms/tags",
            dataType: 'json',
            data: function(term, page) {
                return {
                    term: term, // search term
                    page_limit: 10
                };
            },
            results: function(data, page) {
                return {results: data};
            }
        },
        escapeMarkup: function (m) { return m; }
    };

    $scope.search = function() {
        var searchObject = {};

        if (this.filter_topic) {
            searchObject.topicId = this.filter_topic;
        }
        if (this.lastLogin) {
            searchObject.lastLogin = this.lastLogin;
        }

        // convert tags array to string
        var filterTags = _.reduce(this.filterTags, function(memo, tag){
            if (memo.length > 0) {
                return memo + "," + tag.text
            } else {
                return tag.text;
            }
        }, "");

        if (filterTags.length > 0) {
            searchObject.platformTags = filterTags;
        }

        $scope.platforms = Platform.search(searchObject);
    }

    $scope.openSendOrderDialog = function(receiverPlatform) {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: $scope.routePrefix + "/partials/requests/send_request_dialog.html",
            controller: 'SendOrderDialogController',
            resolve: {
                receiverPlatformId: function() {
                    return angular.copy(receiverPlatform.id);
                }
            }
        });

        dialog.open().then(function(result) {
            if (result) {
                // do nothing...
            }
        });
    }
}

PlatformsSearchController.$inject = ['$scope', '$dialog', 'Platform'];

function SendOrderDialogController($scope, Order, Platform, dialog, receiverPlatformId)
{
    $scope.order = new Order();

    $scope.platforms = Platform.query();

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.sendOrder = function() {
        $scope.order.senderPlatformId = $scope.platform.id;
        $scope.order.receiverPlatformId = receiverPlatformId;
        $scope.order.$send({}, function(order, headers) {
            dialog.close(order);
        }, function(errors) {
            alert("Error ! Details in the console log");
            console.log(errors);
        });
    }
}

SendOrderDialogController.$inject = ['$scope', 'Order', 'Platform', 'dialog', 'receiverPlatformId'];
