/**
 * Created by Alsheuski Alexei on 10/02/16.
 * File: patients.service.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.patient.notes.service', [])
    .service('notesService', notesService);

  notesService.$inject = ['$http', '$q', '$log', 'routeService', 'toaster'];

  function notesService($http, $q, $log, routeService, toaster){

    var self = this;
    self.notes = [];
    self.currentNote = {};
    self.offset = 0;
    self.inProgress = false;
    self.verifyingInProgress = false;
    self.getData = getData;
    self.getNoteData = getNoteData;

    /**
     * Getting notes data from server
     * @returns {d.promise|*|promise}
     */
    function getData (params){

      var def = $q.defer();
      var queryConfig = {
        method: 'GET',
        url: routeService.patient_notes_list_data,
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
        $log.info('Getting notes data success!', res.data);
        self.notes = res.data;
        def.resolve(res.data);
      }

      function onError(err){
        $log.error('Getting notes data error!', err);
        def.reject(err);
      }

      return def.promise;

    }

    /**
     * Get note data by id
     * @param {Object} params
     * @param {int} params.id
     */
    function getNoteData (params){

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

        self.currentNote = {};

        $http(queryConfig)
          .then(onSuccess, onError)
          .finally(function () {
            self.inProgress = false;
          });
      }

      function onSuccess(res){
        $log.info('Got note details data.', res);
        self.currentNote = res.data.response;
        def.resolve(res.data.response);
      }

      function onError(err){
        $log.error('Got note details data error!', err);
        def.reject(err);
      }

      return def.promise;
    }

    return self;

  }

})();