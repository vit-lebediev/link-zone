'use strict';

function PlatformsSearchController($scope, $dialog, Platform)
{
    var urlPrefix = '/app_dev.php';

    $scope.search = function() {
        $scope.platforms = Platform.search({
            topicId: this.filter_topic
        });
    }

    $scope.openSendOrderDialog = function(receiverPlatform) {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: urlPrefix + "/partials/requests/send_request_dialog.html",
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
    var urlPrefix = '/app_dev.php';

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
