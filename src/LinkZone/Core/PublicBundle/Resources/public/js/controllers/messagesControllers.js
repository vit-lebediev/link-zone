'use strict';

function MessagesController($scope, Dialog)
{
    $scope.dialogues = Dialog.getAll();
}

MessagesController.$inject = ['$scope', 'Dialog'];

function DialogsController($scope, $routeParams, $http, Dialog, Message)
{
    $scope.dialog = Dialog.get({dialogId: $routeParams.dialogId});

    $scope.sendMessageOnEnterPress = function ($event)
    {
        $event.preventDefault();
        $scope.sendMessage();
    }

    $scope.sendMessage = function()
    {
        // We use $http.post here instead of service because we did not create message service (we don't need it, I guess...)
        $http.post($scope.routePrefix + '/api/messages/send', {
            senderPlatformId: $scope.dialog.myPlatform.id,
            receiverPlatformId: $scope.dialog.companionPlatform.id,
            message: $scope.message
        })
        .success(function (data, status) {
            $scope.dialog.messages.push({
                message: $scope.message,
                isIncoming: false,
                isNotIncoming: true
            });
            $scope.message = null;
        })
        .error(function (data, status){
            console.log ("Some error occured while sending message");
        });
    }

    $scope.loadMessages = function()
    {
        Message.loadMoreForDialog({dialogId: $scope.dialog.id, offset: $scope.dialog.messages.length}, function(messages, headers) {
            $scope.dialog.messages = messages.concat($scope.dialog.messages);
        });
    }
}

DialogsController.$inject = ['$scope', '$routeParams', '$http', 'Dialog', 'Message'];
