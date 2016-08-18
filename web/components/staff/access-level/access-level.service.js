/**
 * Created by Alsheuski Alexei on 02/03/16.
 * File: access-level.service.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.accessLevel.accessLevelService', [])
      .service('accessLevelService', accessLevelService);

  accessLevelService.$inject = [
    '$http',
    '$log',
    '$q',
    'routeService',
    'toaster'
  ];


  function accessLevelService(
      $http,
      $log,
      $q,
      routeService,
      toaster
  ){

    var self = this;
    self.accessData = [];
    self.serverData = [];
    self.requestInProgress = false;
    self.savingAccessLevelRequestInProgress = false;
    self.getData = getData;
    self.submitAccessLevelsData = submitAccessLevelsData;
    self.processServerData = processServerData;


    /**
     * Submit processed access level data to server
     * @returns {*}
     */
    function submitAccessLevelsData(){

      var def = $q.defer();
      var queryString = {
        method: 'POST',
        url: routeService.staff_submit_access_level_data,
        params: {
          'access_grid': prepareAccessDataToSubmit()
        }
      };

      if(!self.savingAccessLevelRequestInProgress){
        self.savingAccessLevelRequestInProgress = true;

        $http(queryString)
            .then(onSuccess, onError)
            .finally(function(){
              self.savingAccessLevelRequestInProgress = false;
            });
      }

      function onSuccess(res){
        $log.info('Saving access level data was successful!', res.data);

        toaster.pop({
          type: 'success',
          title: 'Success',
          body: 'Access levels successfully saved!',
          showCloseButton: true,
          bodyOutputType: 'trustedHtml'
        });

        def.resolve(res.data);
      }

      function onError(err){
        $log.error('Saving access level data error!', err);
        def.reject(err);
      }

      return def.promise;
    }

    /**
     * Helper method for creating acceess data object for submiting on server
     * @returns {Object} data
     */
    function prepareAccessDataToSubmit(){

      var data = {};
      
      for(var i = 0; i < self.accessData.length; i++){
        data[self.accessData[i].levelName] = getCheckedAccessLevels(self.accessData[i].levelGrid);
      }

      return data;
    }

    /**
     * Helper method for getting only checked access fields for role
     * @param grid
     * @returns {Array}
     */
    function getCheckedAccessLevels(grid){
      var properties = [];

      for(var item in grid){
        for(var i = 0; i < grid[item].accessRoleData.length; i++){
          if(grid[item].accessRoleData[i].isGranted){
            properties.push(grid[item].accessRoleData[i].role);
          }
        }
      }

      return properties;
    }


    /**
     * Get access level data for grid
     * @returns {*}
     */
    function getData(){

      var def = $q.defer();
      var queryString = {
        method: 'POST',
        url: routeService.staff_access_level_data
      };

      if(!self.requestInProgress){
        self.requestInProgress = true;

        self.accessData = [];
        self.serverData = [];

        $http(queryString)
            .then(onSuccess, onError)
            .finally(function(){
              self.requestInProgress = false;
            });
      }

      function onSuccess(res){
        $log.info('Getting access level data was successful!', res.data);
        self.serverData = res.data.response;
        self.processServerData();
        def.resolve(res.data.response);
      }

      function onError(err){
        $log.error('Getting access level data error!', err);
        def.reject(err);
      }

      return def.promise;
    }

    function processServerData(){
      var accessGridData = self.serverData.access_grid;
      var accessLevelsData = self.serverData.access_levels;

      for(var key in accessLevelsData){
        self.accessData.push({
          'levelName': key,
          'levelTitle': accessLevelsData[key].title,
          'levelGrid': processGridDataForLevel(accessLevelsData[key], accessGridData)
        })
      }
    }

    /**
     * Create role access level record row for display on page
     * @param level
     * @param greedData
     * @returns {Array}
     */
    function processGridDataForLevel(level, greedData){
      var data = [];

      for(var key in greedData){
        data.push({
          'accessRoleName': key,
          'accessRoleData': processRoleData(level, greedData[key])
        })
      }

      return data;
    }

    /**
     * Create role access level record item for display on page
     * @param level
     * @param accessData
     * @returns {Array}
     */
    function processRoleData(level, accessData){
      var data = [];

      for(var i = 0; i < accessData.length; i++){

        data.push({
          'role': accessData[i].role,
          'roleTitle': accessData[i].title,
          'isGranted': isAccessGranted(level.checked, accessData[i])
        })
      }

      return data;
    }

    /**
     * Check for existing access level in role's access list
     * @param roleAccessList
     * @param accessDataGreed
     * @returns {boolean}
     */
    function isAccessGranted(roleAccessList, accessDataGreed){

      var isGranted = false;

      for(var i = 0; i < roleAccessList.length; i++){
        if(roleAccessList[i] == accessDataGreed.role){
          isGranted = true;
        }
      }

      return isGranted;
    }

    return self;

  }

})();