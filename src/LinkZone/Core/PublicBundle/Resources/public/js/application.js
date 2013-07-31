'use strict';

var LinkZone = angular.module("LinkZone", ['publicServices', 'ui.bootstrap', 'tags-input']);
console.log("From application");
LinkZone.config(['$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {
        console.log("From config");
        var urlPrefix = "/app_dev.php";

        var authInterceptor = ['$rootScope', '$q', function(scope, $q) {
            var success = function (response) {
                return response;
            }

            var error = function (response) {
                var status = response.status;

                if (status == 401) {
                    alert('redirect to: login');
                    window.location.href = urlPrefix + "/login";
                    return;
                }
                // otherwise
                return $q.reject(response);
            }

            return function(promise) {
                return promise.then(success, error);
            }
        }];
        $httpProvider.responseInterceptors.push(authInterceptor);

        $locationProvider.html5Mode(true);
        $routeProvider
            .when("/app_dev.php/login", {templateUrl: urlPrefix + "/partials/default/login.html", controller: LoginController})
            .when(urlPrefix + "/platforms", {templateUrl: urlPrefix + "/partials/platforms/list.html", controller: PlatformsController})
            .when(urlPrefix + "/platforms/search", {templateUrl: urlPrefix + "/partials/platforms/search.html", controller: PlatformsSearchController})
            .otherwise({redirectTo: "/app_dev.php/login"})
    }]);

console.log("From application after config");
