'use strict';

var outerOpenSendMessageDialog = function ($dialog)
{
    var urlPrefix = '/app_dev.php';

    return function (order) {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: urlPrefix + "/partials/messages/send_message_dialog.html",
            controller: 'SendMessageDialogController',
            resolve: {
                order: function() {
                    return angular.copy(order);
                }
            }
        });

        dialog.open().then(function(result) {
            if (result) {
                // do nothing
            }
        });
    }
}

function OrdersForExchangeController($scope, $dialog, Order)
{
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

    $scope.openSendMessageDialog = outerOpenSendMessageDialog($dialog);

    $scope.openDeleteOrderDialog = function(order) {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: urlPrefix + "/partials/requests/delete_order_dialog.html",
            controller: 'DeleteOrderDialogController',
            resolve: {
                orderId: function() {
                    return angular.copy(order.id);
                },
                receiverPlatformUrl: function() {
                    return angular.copy(order.receiverPlatform.url)
                }
            }
        });

        dialog.open().then(function(result) {
            if (result) {
                // TODO: remove order from order list
            }
        });
    }
}

OrdersForExchangeController.$inject = ['$scope', '$dialog', 'Order'];

function ReviewOrderDialogController($scope, Order, dialog, orderId)
{
    var urlPrefix = '/app_dev.php';

    // fetch data for corresponding order and fill in the form
    $scope.order = Order.get({orderId: orderId});

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.denyOrder = function() {
        $scope.order.$deny({orderId: $scope.order.id}, function(order, headers) {
            dialog.close(order);
        }, function(errors) {
            // handle errors
            alert("Error ! Details in the console log");
            console.log(errors);
        });
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

ReviewOrderDialogController.$inject = ['$scope', 'Order', 'dialog', 'orderId'];

function DeleteOrderDialogController($scope, Order, dialog, orderId, receiverPlatformUrl)
{
    var urlPrefix = '/app_dev.php';

    $scope.receiverPlatformUrl = receiverPlatformUrl;

    $scope.deleteOrder = function() {
        new Order().$delete({orderId: orderId}, function(order, headers) {
            // success
            dialog.close();
        }, function(errors) {
            // handle errors
            alert("Error ! Details in the console log");
            console.log(errors)
        });
    }

    $scope.close = function(result) {
        dialog.close(result);
    }
}

DeleteOrderDialogController.$inject = ['$scope', 'Order', 'dialog', 'orderId', 'receiverPlatformUrl'];

function OrdersInProgressController($scope, $dialog, Order)
{
    $scope.orders = Order.inProgress(function() {
        for (var i = 0; i < $scope.orders.length; i++) {
            // TODO: Implement better way to bind to these values from view
            var order = $scope.orders[i];
            order.hisLinkLocation  = order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;
            order.myLinkLocation = order.isIncoming ? order.receiverLinkLocation : order.senderLinkLocation;
        }
    });

    $scope.saveReceiverLinkLocation = function (order) {
        if (order.isIncoming) order.senderLinkLocation = order.hisLinkLocation;
        else                  order.receiverLinkLocation = order.hisLinkLocation;

        order.$saveLinkLocation({orderId: order.id}, function() {
            order.hisLinkLocation  = order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;
            order.myLinkLocation   = !order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;
        });
    }

    $scope.acceptOrder = function(order) {
        if (order.isIncoming) order.receiverAccepted = true;
        else                  order.senderAccepted   = true;

        order.$acceptOrCancel({orderId: order.id}, function() {
            order.hisLinkLocation  = order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;
            order.myLinkLocation   = !order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;
        });
    }

    $scope.cancelAcceptedOrder = function(order) {
        if (order.isIncoming) order.receiverAccepted = false;
        else                  order.senderAccepted   = false;

        order.$acceptOrCancel({orderId: order.id}, function() {
            order.hisLinkLocation  = order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;
            order.myLinkLocation   = !order.isIncoming ? order.senderLinkLocation : order.receiverLinkLocation;

            // TODO: check if another side has accepted, and remove from the list if yes.
        });
    }

    $scope.openSendMessageDialog = outerOpenSendMessageDialog($dialog);
}

OrdersInProgressController.$inject = ['$scope', '$dialog', 'Order'];

function OrdersFinishedController($scope, $dialog, Order)
{
    $scope.orders = Order.fetchFinished();

    $scope.openSendMessageDialog = outerOpenSendMessageDialog($dialog);
}

OrdersFinishedController.$inject = ['$scope', '$dialog', 'Order'];

function SendMessageDialogController($scope, $http, dialog, order)
{
    var urlPrefix = '/app_dev.php';

    $scope.close = function(result)
    {
        dialog.close(result);
    }

    $scope.sendMessage = function()
    {
        $http.post(urlPrefix + '/api/messages/send', {
            senderPlatformId: (order.isIncoming ? order.receiverPlatform.id : order.senderPlatform.id),
            receiverPlatformId: (!order.isIncoming ? order.receiverPlatform.id : order.senderPlatform.id),
            message: $scope.message
        })
        .success(function (data, status) {
            dialog.close(data.message);
        })
        .error(function (data, status){
            console.log("Error: " + status);
            dialog.close();
        });
    }
}

SendMessageDialogController.$inject = ['$scope', '$http', 'dialog', 'order']
