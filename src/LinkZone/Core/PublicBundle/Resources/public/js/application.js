'use strict';

var LinkZone = angular.module("LinkZone", ['publicServices', 'ui.bootstrap', 'ui.utils', 'tags-input']);
LinkZone.config(['$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {

        var routePrefix = "/app_dev.php"; // TODO: this is to change on production

        var authInterceptor = ['$rootScope', '$q', function(scope, $q) {
            var success = function (response) {
                return response;
            }

            var error = function (response) {
                var status = response.status;

                if (status == 401) {
                    alert('redirect to: login');
                    window.location.href = routePrefix + "/login";
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
            .when(routePrefix + "/login", {templateUrl: routePrefix + "/partials/default/login.html", controller: LoginController})
            .when(routePrefix + "/logout", {templateUrl: routePrefix + "/partials/default/login.html", controller: LogoutController})
            .when(routePrefix + "/platforms", {templateUrl: routePrefix + "/partials/platforms/list.html", controller: PlatformsController})
            .when(routePrefix + "/platforms/search", {templateUrl: routePrefix + "/partials/platforms/search.html", controller: PlatformsSearchController})
            .when(routePrefix + "/orders/for-exchange", {templateUrl: routePrefix + "/partials/requests/for_exchange.html", controller: OrdersForExchangeController})
            .when(routePrefix + "/orders/in-progress", {templateUrl: routePrefix + "/partials/requests/in_progress.html", controller: OrdersInProgressController})
            .when(routePrefix + "/orders/finished", {templateUrl: routePrefix + "/partials/requests/finished.html", controller: OrdersFinishedController})
            .when(routePrefix + "/messages", {templateUrl: routePrefix + "/partials/messages/list.html", controller: MessagesController})
            .when(routePrefix + "/messages/dialog/:dialogId", {templateUrl: routePrefix + "/partials/messages/dialog.html", controller: DialogsController})
            .otherwise({redirectTo: routePrefix + "/login"})
    }]);

LinkZone.run(function($rootScope) {
    $rootScope.routePrefix = "/app_dev.php"; // TODO: this is to change on production
});
