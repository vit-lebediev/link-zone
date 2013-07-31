'use strict';

function LoginController($scope, $http, $location) {
    $scope.submit = function () {
        var urlPrefix = '/app_dev.php';

        $http.post(urlPrefix + '/login_check', {
            _username: this.username,
            _password: this.password,
            _csrf_token: angular.element(document.getElementById('_csrf_token')).val(),
            _remember_me: this.remember_me
        }, {
            // http://stackoverflow.com/questions/11442632/how-can-i-make-angular-js-post-data-as-form-data-instead-of-a-request-payload
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status) {
            $location.path(urlPrefix + '/platforms')
        }).
        error(function(data, status) {
            console.log(status + ": " + data);
        });
    }
}

LoginController.$inject = ['$scope', '$http', '$location'];

function PlatformsController($scope, $dialog, Platform) {
    $scope.platforms = Platform.query();
    console.log("From platforms controller");

    var urlPrefix = '/app_dev.php';

    $scope.openAddPlatformDialog = function () {
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

    $scope.openEditPlatformDialog = function (platformId) {
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
}

PlatformsController.$inject = ['$scope', '$dialog', 'Platform'];

function AddPlatformDialogController($scope, $http, Platform, dialog) {
    var urlPrefix = '/app_dev.php';

    $scope.platform = new Platform();

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.addPlatform = function() {
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

function EditPlatformDialogController($scope, $http, Platform, dialog, platformId) {
    $scope.editing = true;

    // fetch data for corresponding platform and fill in the form
    $scope.platform = Platform.get({platformId: platformId});

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.editPlatform = function () {
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

EditPlatformDialogController.$inject = ['$scope', '$http', 'Platform', 'dialog', 'platformId'];

function PlatformsSearchController($scope, Platform) {
    $scope.platforms = Platform.search({test: "tset"}); // params work!

    $scope.search = function() {
        $scope.platforms = Platform.search({
            topicId: this.filter_topic
        });
    }
}

PlatformsSearchController.$inject = ['$scope', 'Platform'];
