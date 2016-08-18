/**
 * Created by Alsheuski Alexei on 02/03/16.
 * File: access-level.directive.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.accessLevel.accessLevelDirective', [])
      .directive('kcAccessLevels', kcAccessLevels);

  kcAccessLevels.$inject = [
      'accessLevelService',
      '$log'
  ];

  function kcAccessLevels(
      accessLevelService,
      $log
  ){

    return {
      restrict: 'A',
      //scope: {},
      bindToController: true,
      controller: controller,
      controllerAs: 'accessLevelCtrl',
      link: link
    };

    function controller(){
      var self = this;
      self.dataSource = accessLevelService;
      self.accessLevelDropdownOptions = [];
      self.accessLevelDropdownSelected = {};
      self.saveAccessLevel = saveAccessLevel;
      self.onAccessLevelDropdownChange = onAccessLevelDropdownChange;
      self.gridData = [];
      self.maxDataGridColumns = 0;
      self.setMaxDataGridColumns = setMaxDataGridColumns;
      self.initAccessLevelDropbown = initAccessLevelDropbown;
      self.init = init;


      function saveAccessLevel(){
        self.dataSource.submitAccessLevelsData();
      }

      function setMaxDataGridColumns(){
        for(var i=0; i < self.dataSource.accessData.length; i++){
          for(var j = 0; j < self.dataSource.accessData[i].levelGrid.length; j++){
            if(self.dataSource.accessData[i].levelGrid[j].accessRoleData.length > self.maxDataGridColumns){
              self.maxDataGridColumns = self.dataSource.accessData[i].levelGrid[j].accessRoleData.length;
            }
          }
        }
      }

      function onAccessLevelDropdownChange(){
        self.gridData = self.dataSource.accessData[self.accessLevelDropdownSelected.index].levelGrid;
      }

      function initAccessLevelDropbown(){
        for(var i=0; i < self.dataSource.accessData.length; i++){
          self.accessLevelDropdownOptions.push({
            index: i,
            name: self.dataSource.accessData[i].levelTitle
          })
        }

        if(self.accessLevelDropdownOptions.length > 0){
          self.accessLevelDropdownSelected = self.accessLevelDropdownOptions[0];
        }
      }

      function init(){
        $log.info('Access level directive was successfully initiated!');

        self.dataSource
            .getData()
            .then(function(){
              self.initAccessLevelDropbown();
              self.setMaxDataGridColumns();
              self.gridData = self.dataSource.accessData[self.accessLevelDropdownSelected.index].levelGrid;
            });
      }

      self.init();
    }

    function link(scope, el, attr){

    }

  }


})();