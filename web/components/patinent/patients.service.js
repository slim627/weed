/**
 * Created by Alsheuski Alexei on 10/02/16.
 * File: patients.service.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.patient.patientsService', [])
    .service('patientsService', patientsService);

  patientsService.$inject = ['$http', '$q', '$log', 'routeService', 'toaster'];

  function patientsService($http, $q, $log, routeService, toaster){

    var self = this;
    self.patients = [];
    self.currentPatient = {};
    self.offset = 0;
    self.inProgress = false;
    self.verifyingInProgress = false;
    self.getData = getData;
    self.getPatientData = getPatientData;
    self.changePatientVerifyStatus = changePatientVerifyStatus;

    /**
     * Getting patients data from server
     * @returns {d.promise|*|promise}
     */
    function getData (params){

      var def = $q.defer();
      var queryConfig = {
        method: 'GET',
        url: routeService.patient_list_data,
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
        $log.info('Getting patients data success!', res.data);
        self.patients = {};

        //self.patients = res.data;
        def.resolve(res.data);
      }

      function onError(err){
        $log.error('Getting patients data error!', err);
        def.reject(err);
      }

      return def.promise;

    }

    /**
     * Get patient data by id
     * @param {Object} params
     * @param {int} params.id
     */
    function getPatientData (params){

      var def = $q.defer();

      var queryConfig = {
        method: 'GET',
        url: routeService.patient_show_data,
        params: {
          id: params.id
        }
      };

      if(!self.inProgress) {
        self.inProgress = true;

        self.currentPatient = {};

        $http(queryConfig)
          .then(onSuccess, onError)
          .finally(function () {
            self.inProgress = false;
          });
      }

      function onSuccess(res){
        $log.info('Got patient details data.', res);
        self.currentPatient = res.data.response;
        def.resolve(res.data.response);
      }

      function onError(err){
        $log.error('Got patient details data error!', err);
        def.reject(err);
      }

      return def.promise;
    }


      /**
       * Changing patient verify status
       * @param {Object} params
       * @param {bool} params.verifiedStatus
       * @returns {*}
       */
    function changePatientVerifyStatus(params){
      var def = $q.defer();

      var queryConfig = {
        method: 'POST',
        url: routeService.patient_verify,
        params: {
          is_verified: params.verifiedStatus,
          id: self.currentPatient.id
        }
      };

      if(!self.verifyingInProgress){
        self.verifyingInProgress = true;

        $http(queryConfig)
            .then(onSuccess, onError)
            .finally(function () {
              self.verifyingInProgress = false;
            });
      }

      function onSuccess(res){
        $log.info('Patient\'s verified status successfully changed.');

        if(self.currentPatient.hasOwnProperty('verified')){
          self.currentPatient.verified = params.verifiedStatus;
        }

        toaster.pop({
          type: 'success',
          title: 'Success',
          body: 'Patient\'s verify status successfully changed!',
          showCloseButton: true,
          bodyOutputType: 'trustedHtml'
        });

        def.resolve(res.data);
      }

      function onError(err){
        $log.error('Patient\'s verified status changing error!', err);
        def.reject(err);
      }

      return def.promise;
    }

    return self;

  }

})();