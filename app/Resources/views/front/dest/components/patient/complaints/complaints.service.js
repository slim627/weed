/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: complaints.service.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.patient.complaints.complaintsService', [])
        .service('complaintsService', complaintsService);

    complaintsService.$inject = ['$http', '$q', '$log', 'routeService'];

    function complaintsService($http, $q, $log, routeService){
        var self = this;
        self.items = [];
        self.currentItem = {};
        self.offset = 0;
        self.inProgress = false;
        self.getData = getData;
        self.getTasksListForComplaint = getTasksListForComplaint;

        /**
         * Getting patient complaints data from server
         * @returns {d.promise|*|promise}
         */
        function getData(params){

            var def = $q.defer();
            var queryConfig = {
                method: 'GET',
                url: routeService.patient_complaints_list_data,
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


        function getTasksListForComplaint(params){

            var def = $q.defer();

            var queryString = {
                method: 'POST',
                url: routeService.patient_complaint_tasks_show_data,
                params: {
                    id: params.complaintId
                }
            };

            $http(queryString)
                .then(onSuccess, onError);

            function onSuccess(res){
                $log.info('Getting complaint tasks data success!', res.data);
                def.resolve(res.data);
            }

            function onError(err){
                $log.error('Getting complaints tasks data error!', err);
                def.reject(err);
            }


            return def.promise;
        }

        return self;
    }

})();
