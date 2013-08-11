'use strict';

function OrdersForExchangeController($scope, $dialog, Order) {

    var urlPrefix = '/app_dev.php';

    $scope.sentOrders = Order.forExchangeSent();
    $scope.receivedOrders = Order.forExchangeReceived();

    $scope.openReviewOrderDialog = function(orderId) {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: urlPrefix + "/partials/requests/review_request_dialog.html",
            controller: 'ReviewOrderDialogController',
            resolve: {
                orderId: function() {
                    return angular.copy(orderId);
                }
            }
        });

        dialog.open().then(function(result) {
            if (result) {
                // TODO: remove order from order list
            }
        });
    }

    $scope.sendMessage = function(senderPlatformId, receiverPlatformId) {

    }

    $scope.deleteOrder = function(orderId) {

    }
}

OrdersForExchangeController.$inject = ['$scope', '$dialog', 'Order'];

function ReviewOrderDialogController($scope, Order, dialog, orderId) {
    var urlPrefix = '/app_dev.php';

    // fetch data for corresponding order and fill in the form
    $scope.order = Order.get({orderId: orderId});

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.denyOrder = function() {

    }

    $scope.approveOrder = function() {
        $scope.order.$approve({orderId: $scope.order.id}, function(order, headers) {
            dialog.close(order);
        }, function(errors) {
            // handle errors
            alert("Error ! Details in the console log");
            console.log(errors);
        });
    }
}

ReviewOrderDialogController.$inject = ['$scope', 'Order', 'dialog', 'orderId']
