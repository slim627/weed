/**
 * Created by Alsheuski Alexei on 15/02/16.
 * File: dashboard.controller.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.controllers.dashboard', [])
    .controller('dashboardController', dashboardController);

  dashboardController.$inject = ['$rootScope', '$scope'];

  function dashboardController($rootScope, $scope){

    $scope.$on('$viewContentLoaded', function() {
      // initialize core modules
      App.initAjax();
    });

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageSidebarClosed = false;

  }

})();