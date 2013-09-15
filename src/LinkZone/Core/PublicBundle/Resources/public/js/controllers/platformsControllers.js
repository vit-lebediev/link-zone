'use strict';

function PlatformsController($scope, $dialog, Platform)
{
    $scope.platforms = Platform.query();

    var urlPrefix = '/app_dev.php';

    $scope.openAddPlatformDialog = function ()
    {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: "partials/platforms/add_dialog.html",
            controller: 'AddPlatformDialogController'
        });

        dialog.open().then(function(result) {
            if (result) {
                $scope.platforms.unshift(result);
            }
        });
    }

    $scope.openEditPlatformDialog = function (platformId)
    {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: "partials/platforms/edit_dialog.html",
            controller: 'EditPlatformDialogController',
            resolve: {
                platformId: function() {
                    return angular.copy(platformId);
                }
            }
        });

        dialog.open().then(function(platform) {
            // TODO: edit corresponding platform in the list of platforms
        });
    }

    $scope.openConfirmPlatformDialog = function(platform)
    {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            dialogFade: true,
            backdropFade: true,
            templateUrl: "partials/platforms/confirm_dialog.html",
            controller: 'ConfirmPlatformDialogController',
            resolve: {
                platform: function() {
                    return angular.copy(platform);
                }
            }
        });

        dialog.open().then(function(platform) {
            // do nothing...
        });
    }

    $scope.isPlatformStatus = function(platform, platformStatus) {
        return platform.status_code === platformStatus;
    }
}

PlatformsController.$inject = ['$scope', '$dialog', 'Platform'];

function AddPlatformDialogController($scope, $http, Platform, dialog)
{
    var urlPrefix = '/app_dev.php';

    $scope.platform = new Platform();

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.addPlatform = function()
    {
        var platform = {
            url: this.platform.url,
            topic: this.platform.topic_id,
            description: this.platform.description,
            hidden: this.platform.hidden,
            _token: angular.element(document.getElementById('platform__token')).val()
        }

        $http.post(urlPrefix + '/api/platforms/add', {
            platform: platform
        }, {
//             http://stackoverflow.com/questions/11442632/how-can-i-make-angular-js-post-data-as-form-data-instead-of-a-request-payload
            transformRequest: function(obj) {
                return $.param(obj);
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).
        success(function (data, status) {
            dialog.close(data.platform);
        }).
        error(function(data, status) {
            alert("Error: " + status);
        });
    }

    $scope.tags = ['tag1', 'tag2'];
}

AddPlatformDialogController.$inject = ['$scope', '$http', 'Platform', 'dialog'];

function EditPlatformDialogController($scope, Platform, dialog, platformId)
{
    $scope.editing = true;

    // fetch data for corresponding platform and fill in the form
    $scope.platform = Platform.get({platformId: platformId});

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.editPlatform = function ()
    {
        // save platform to DB
        $scope.platform.$save({platformId: $scope.platform.id}, function(platform, headers) {
            dialog.close(platform);
        }, function(errors) {
            // handle errors
            alert("Error ! Details in the console log");
            console.log(errors)
        });
    }
}

EditPlatformDialogController.$inject = ['$scope', 'Platform', 'dialog', 'platformId'];

function ConfirmPlatformDialogController($scope, dialog, platform)
{
    $scope.activationMethod = 'html_tag';
    $scope.activation_random_string = 'test';

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.isActivationMethod = function(method) {
        return $scope.activationMethod === method;
    }
}

ConfirmPlatformDialogController.$inject = ['$scope', 'dialog', 'platform'];
