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
            window.location.href = urlPrefix + '/platforms';
//            $location.path(urlPrefix + '/platforms');
        }).
        error(function(data, status) {
            console.log(status + ": " + data);
        });
    }
}

LoginController.$inject = ['$scope', '$http', '$location'];

function LogoutController() {
    var urlPrefix = '/app_dev.php';
    window.location.href = urlPrefix + '/logout';
}
