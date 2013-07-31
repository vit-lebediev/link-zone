'use strict';
console.log("From services");
angular.module('publicServices', ['ngResource']).
    factory('Platform', function($resource){
        return $resource('/app_dev.php/api/platforms/:platformId.json', {}, {
            query: {method: 'GET', params: {platformId: 'all'}, isArray: true},
            search: {method: 'GET', params: {platformId: 'search'}, isArray: true}
        });
    });
