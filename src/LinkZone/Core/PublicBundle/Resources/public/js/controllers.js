'use strict';

function PlatformsController($scope, $dialog, Platform) {
    $scope.platforms = Platform.query();
    console.log("From platforms controller");

    var urlPrefix = '/app_dev.php';

    $scope.openAddPlatformDialog = function () {
        var dialog = $dialog.dialog({
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            templateUrl: "partials/platforms/platform_dialog.html",
            controller: 'AddPlatformDialogController'
        });

        dialog.open().then(function(result) {
            if (result) {
                $scope.platforms.unshift(result);
            }
        });
    }
}

PlatformsController.$inject = ['$scope', '$dialog', 'Platform'];

function AddPlatformDialogController ($scope, $http, dialog) {
    var urlPrefix = '/app_dev.php';

    $scope.close = function(result) {
        dialog.close(result);
    }

    $scope.addPlatform = function() {
        var platform = {
            url: this.platform_url,
            topic: this.platform_topic,
            description: this.platform_descr,
            hidden: this.platform_hidden,
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
            alert("Added successfully");
            dialog.close(data.platform);
        }).
        error(function(data, status) {
            alert("Error: " + status);
        });
    }

    $scope.tags = ['tag1', 'tag2'];
}

AddPlatformDialogController.$inject = ['$scope', '$http', 'dialog'];

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
