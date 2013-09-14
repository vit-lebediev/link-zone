'use strict';

var LinkZone = angular.module("LinkZone", ['publicServices', 'ui.bootstrap', 'tags-input']);
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
            .when(urlPrefix + "/login", {templateUrl: urlPrefix + "/partials/default/login.html", controller: LoginController})
            .when(urlPrefix + "/logout", {templateUrl: urlPrefix + "/partials/default/login.html", controller: LogoutController})
            .when(urlPrefix + "/platforms", {templateUrl: urlPrefix + "/partials/platforms/list.html", controller: PlatformsController})
            .when(urlPrefix + "/platforms/search", {templateUrl: urlPrefix + "/partials/platforms/search.html", controller: PlatformsSearchController})
            .when(urlPrefix + "/orders/for-exchange", {templateUrl: urlPrefix + "/partials/requests/for_exchange.html", controller: OrdersForExchangeController})
            .when(urlPrefix + "/orders/in-progress", {templateUrl: urlPrefix + "/partials/requests/in_progress.html", controller: OrdersInProgressController})
            .when(urlPrefix + "/orders/finished", {templateUrl: urlPrefix + "/partials/requests/finished.html", controller: OrdersFinishedController})
            .when(urlPrefix + "/messages", {templateUrl: urlPrefix + "/partials/messages/list.html", controller: MessagesController})
            .when(urlPrefix + "/messages/dialog/:dialogId", {templateUrl: urlPrefix + "/partials/messages/dialog.html", controller: DialogsController})
            .otherwise({redirectTo: "/app_dev.php/login"})
    }]);
