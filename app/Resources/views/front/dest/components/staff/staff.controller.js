/**
 * Created by Alsheuski Alexei on 09/02/16.
 * File: staff.controller.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.staff.staffController', [])
    .controller('staffController', staffController);

  staffController.$inject = [
    '$rootScope',
    '$scope',
    'doctorService',
    '$state',
    '$log',
    'ngDialog',
    'routeService'
  ];

  function staffController (
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
    self.doUpdateTable = false;
    self.initDialogOpenWatcher = initDialogOpenWatcher;
    self.dialogOpenInProgress = false;
    self.refreshTable = refreshTable;
    self.filterCheckbox = {};
    self.init = init;


    function refreshTable(){
      self.doUpdateTable = !self.doUpdateTable;
    }

    function add(){
      if (!self.dialogOpenInProgress) {
        self.dialogOpenInProgress = true;

        ngDialog.open({
          template: routeService.staff_create,
          controller: 'staffModalController',
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
          template: routeService.staff_edit + '?id=' + params.id,
          controller: 'staffModalController',
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
      $log.info('Staff list controller initiated.');
      self.initDialogOpenWatcher();
    }

    self.init();

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageSidebarClosed = false;

  }

})();