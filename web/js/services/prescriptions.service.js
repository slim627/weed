/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: prescriptions.service.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.services.prescriptionsService', [])
        .service('prescriptionsService', prescriptionsService);

    prescriptionsService.$inject = ['$http', '$q', '$log', 'routeService'];

    function prescriptionsService($http, $q, $log, routeService){
        var self = this;
        self.items = [];
        self.currentItem = {};
        self.offset = 0;
        self.inProgress = false;
        self.getData = getData;

        /**
         * Getting patient complaints data from server
         * @returns {d.promise|*|promise}
         */
        function getData(params){

            var def = $q.defer();
            var queryConfig = {
                method: 'GET',
                url: routeService.patient_prescriptions_list_data,
                params: {
                    limit: params.limit,
                    offset: parseInt(params.page-1) * parseInt(params.limit),
                    filterString: $.param(params.filter)
                }
            };

            // if request in progress then do nothing
            if(!self.inProgress){
                self.inProgress = true;

                $http(queryConfig)
                    .then(onSuccess, onError)
                    .finally(function () {
                        self.inProgress = false;
                    });
            }

            function onSuccess(res){
                $log.info('Getting complaints data success!', res.data);
                self.items = res.data;
                def.resolve(res.data);
            }

            function onError(err){
                $log.error('Getting complaints data error!', err);
                def.reject(err);
            }


            return def.promise;

        }


        return self;
    }

})();
