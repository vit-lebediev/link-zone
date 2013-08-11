'use strict';
console.log("From services");
angular.module('publicServices', ['ngResource']).
    factory('Platform', function($resource){
        return $resource('/app_dev.php/api/platforms/:platformId.json', {}, {
            query: {method: 'GET', params: {platformId: 'all'}, isArray: true},
            search: {method: 'GET', params: {platformId: 'search'}, isArray: true}
        });
    })
    .factory('Order', function($resource){
        return $resource('/app_dev.php/api/orders/:orderId', {}, {
            forExchangeSent: {method: 'GET', params: {orderId: 'all', status: 'exchange-sent'}, isArray: true},
            forExchangeReceived: {method: 'GET', params: {orderId: 'all', status: 'exchange-received'}, isArray: true},
            approve: {method: 'POST', params: {action: 'approve'}}
        })
    });
