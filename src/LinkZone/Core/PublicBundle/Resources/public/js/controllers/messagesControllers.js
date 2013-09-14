'use strict';

function MessagesController($scope, Dialog)
{
    $scope.urlPrefix = '/app_dev.php';

    $scope.dialogues = Dialog.getAll();
}

MessagesController.$inject = ['$scope', 'Dialog'];

function DialogsController($scope, $routeParams, $http, Dialog)
{
    var urlPrefix = '/app_dev.php';

    $scope.dialog = Dialog.get({dialogId: $routeParams.dialogId});

    $scope.sendMessage = function()
    {
        // We use $http.post here instead of service because we did not create message service (we don't need it, I guess...)
        $http.post(urlPrefix + '/api/messages/send', {
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
            console.log ("Some error occured while sending message")
        });
    }
}

DialogsController.$inject = ['$scope', '$routeParams', '$http', 'Dialog'];
