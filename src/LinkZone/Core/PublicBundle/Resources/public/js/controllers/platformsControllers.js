'use strict';

function PlatformsController($scope, $dialog, Platform)
{
    $scope.platforms = Platform.query();

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

        dialog.open().then(function(data) {
            platform.status_code = data.new_status_code;
            platform.status_string = data.new_status_string;
        });
    }

    $scope.isPlatformStatus = function(platform, platformStatus) {
        return platform.status_code === platformStatus;
    }
}

PlatformsController.$inject = ['$scope', '$dialog', 'Platform'];

function AddPlatformDialogController($scope, $http, Platform, dialog)
{
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

        $http.post($scope.routePrefix + '/api/platforms/add', {
            platform: platform
        }, {
            // http://stackoverflow.com/questions/11442632/how-can-i-make-angular-js-post-data-as-form-data-instead-of-a-request-payload
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

function ConfirmPlatformDialogController($scope, $http, dialog, platform)
{
    if (!platform.confirmation_code) {
        // TODO: implement proper error handling
        alert("No confirmation code on the platform !");
        dialog.close();
        return;
    }

    $scope.confirmationMethod = 'HTML_TAG';
    $scope.platform = platform;

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.isConfirmationMethod = function(method) {
        return $scope.confirmationMethod === method;
    }

    $scope.confirmPlatform = function() {
        $http.post($scope.routePrefix + '/api/platforms/' + platform.id + '/confirm', {
            confirmationMethod: $scope.confirmationMethod
        })
        .success(function (data, status) {
            dialog.close(data);
        })
        .error(function (data, status) {
            console.log (status + " Error during platform confirmation: " + data.error_message);
        });
    }
}

ConfirmPlatformDialogController.$inject = ['$scope', '$http', 'dialog', 'platform'];
