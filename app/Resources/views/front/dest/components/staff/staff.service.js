/**
 * Created by Alsheuski Alexei on 10/02/16.
 * File: staff.service.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.staff.staffService', [])
    .service('staffService', staffService);

  staffService.$inject = ['$http', '$q', '$log', 'routeService', 'toaster'];

  function staffService($http, $q, $log, routeService, toaster){

    var self = this;
    self.doctor = [];
    self.currentDoctor = {};
    self.offset = 0;
    self.inProgress = false;
    self.getData = getData;

    /**
     * Getting doctors data from server
     * @returns {d.promise|*|promise}
     */
    function getData (params){

      var def = $q.defer();
      var queryConfig = {
        method: 'GET',
        url: routeService.staff_list_data,
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
        $log.info('Getting staff data success!', res.data);
        self.doctor = res.data;
        def.resolve(res.data);
      }

      function onError(err){
        $log.error('Getting staff data error!', err);
        def.reject(err);
      }

      return def.promise;

    }

    return self;

  }

})();