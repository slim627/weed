/**
 * Created by Alsheuski Alexei on 10/02/16.
 * File: history.service.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.services.historyService', [])
    .service('historyService', historyService);

  historyService.$inject = ['$http', '$q', '$log', 'routeService'];

  function historyService($http, $q, $log, routeService ){

    var self = this;
    self.items = [];
    self.offset = 0;
    self.inProgress = false;
    self.getData = getData;

    /**
     * Getting history data from server
     * @returns {d.promise|*|promise}
     */
    function getData (params){

      var def = $q.defer();
      var queryConfig = {
        method: 'GET',
        url: routeService.patient_histories_list_data,
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
        $log.info('Getting history items data success!', res.data);
        self.items = res.data;
        def.resolve(res.data);
      }

      function onError(err){
        $log.error('Getting history data error!', err);
        def.reject(err);
      }

      return def.promise;

    }

    return self;

  }

})();