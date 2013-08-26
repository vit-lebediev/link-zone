'use strict';

function MessagesController($scope, Dialog)
{
    $scope.urlPrefix = '/app_dev.php';

    $scope.dialogues = Dialog.getAll();
}

MessagesController.$inject = ['$scope', 'Dialog'];

function DialogsController($scope, $routeParams, Dialog)
{
    $scope.dialog = Dialog.get({dialogId: $routeParams.dialogId});
}

DialogsController.$inject = ['$scope', '$routeParams', 'Dialog'];
