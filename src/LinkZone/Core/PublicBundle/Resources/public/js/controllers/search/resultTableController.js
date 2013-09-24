'use strict';

/**
 * Controlling result table view
 *
 */
LinkZone.controller('ResultTableController', ['$scope', 'Platform', 'ngTableParams', function ($scope, Platform, ngTableParams)
{
    // Default table settings
    $scope.tableParams = new ngTableParams({
        page: 1, // show first page
        total: 0, // length of data
        count: 10 // count per page
        /*sorting: {
            name: 'asc' // initial sorting
        }*/
    });

    // If tableParams update data
    $scope.$watch('tableParams', function (params){

        // Set data to table
        $scope.platforms = Platform.search(params.url());

    }, true);
}]);

