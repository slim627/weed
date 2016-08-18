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
      scope: {},
      controller: controller,
      controllerAs: 'accessLevelCtrl',
      link: link
    };

    function controller(){
      var self = this;
      self.dataSource = accessLevelService;
      self.init = init;


      function init(){
        $log.info('Access level directive was successfully initiated!');
        self.dataSource.getData();
      }

      self.init();
    }

    function link(scope, el, attr){

    }

  }


})();