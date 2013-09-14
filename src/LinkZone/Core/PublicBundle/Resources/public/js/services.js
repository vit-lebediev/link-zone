'use strict';
angular.module('publicServices', ['ngResource']).
    factory('Platform', function($resource) {
        return $resource('/app_dev.php/api/platforms/:platformId.json', {}, {
            query: {method: 'GET', params: {platformId: 'all'}, isArray: true},
            search: {method: 'GET', params: {platformId: 'search'}, isArray: true}
        });
    })
    .factory('Order', function($resource) {
        return $resource('/app_dev.php/api/orders/:orderId', {}, {
            forExchangeSent: {method: 'GET', params: {orderId: 'all', status: 'exchange-sent'}, isArray: true},
            forExchangeReceived: {method: 'GET', params: {orderId: 'all', status: 'exchange-received'}, isArray: true},
            approve: {method: 'POST', params: {action: 'approve'}},
            deny: {method: 'POST', params: {action: 'deny'}},
            send: {method: 'POST', params: {orderId: 'send'}},
            inProgress: {method: 'GET', params: {orderId: 'all', status: 'in-progress'}, isArray: true},
            saveLinkLocation: {method: 'POST', params: {action: 'saveLinkLocation'}},
            acceptOrCancel: {method: 'POST', params: {action: 'acceptOrCancel'}},
            fetchFinished: {method: 'GET', params: {orderId: 'all', status: 'finished'}, isArray: true}
        });
    })
    .factory('Dialog', function($resource) {
        return $resource('/app_dev.php/api/messages/dialogues/:dialogId', {}, {
            getAll: {method: 'GET', params: {dialogId: 'all'}, isArray: true}
        })
    })
    .factory('Message', function($resource) {
        return $resource('/app_dev.php/api/messages/dialogues/:dialogId/messages/:messageId', {}, {
            loadMoreForDialog: { method: 'GET', params: { messageId: 'all' }, isArray: true }
        });
    });
