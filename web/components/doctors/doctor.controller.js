/**
 * Created by Alsheuski Alexei on 09/02/16.
 * File: doctor.controller.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.controllers.doctorsController', [])
    .controller('doctorsController', doctorsController);

  doctorsController.$inject = [
    '$rootScope',
    '$scope',
    'doctorService',
    '$state',
    '$log',
    'ngDialog',
    'routeService'
  ];

  function doctorsController (
      $rootScope,
      $scope,
      doctorService,
      $state,
      $log,
      ngDialog,
      routeService
  ){

    var self = this;
    self.add = add;
    self.edit = edit;
    self.initDialogOpenWatcher = initDialogOpenWatcher;
    self.dialogOpenInProgress = false;
    self.init = init;

    function add(){
      if (!self.dialogOpenInProgress) {
        self.dialogOpenInProgress = true;

        ngDialog.open({
          template: routeService.doctor_create,
          controller: 'doctorModalController',
          cache: false,
          resolve: {
            isEdit: function(){
              return false
            }
          },
          controllerAs: 'modalCtrl'
        });
      }
    }

    /**
     * Open edit current doctor dialog
     */
    function edit(params) {
      if (!self.dialogOpenInProgress) {
        self.dialogOpenInProgress = true;

        var dialog = ngDialog.open({
          template: routeService.doctor_edit + '?id=' + params.id,
          controller: 'doctorModalController',
          cache: false,
          resolve: {
            isEdit: function(){
              return true
            }
          },
          controllerAs: 'modalCtrl'
        });
      }
    }

    /**
     * Init watching on open dialog event
     */
    function initDialogOpenWatcher() {
      $rootScope.$on('ngDialog.opened', function (e, $dialog) {
        $log.info('ngDialog opened: ' + $dialog.attr('id'));
        self.dialogOpenInProgress = false;
      });
    }

    function init(){
      $log.info('Doctors list controller initiated.');
      self.initDialogOpenWatcher();
    }

    self.init();

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageSidebarClosed = false;

  }

})();