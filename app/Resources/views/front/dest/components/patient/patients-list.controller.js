/**
 * Created by Alsheuski Alexei on 09/02/16.
 * File: patients-list.controller.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.patient.patientsList', [])
    .controller('PatientsController', PatientsController);

  PatientsController.$inject = [
    '$rootScope',
    '$scope',
    'patientsService',
    '$state',
    '$log',
    'ngDialog',
    'routeService'
  ];

  function PatientsController (
      $rootScope,
      $scope,
      patientsService,
      $state,
      $log,
      ngDialog,
      routeService
  ){

    var self = this;
    self.addNewPatient = addNewPatient;
    self.openPatient = openPatient;
    self.initDialogOpenWatcher = initDialogOpenWatcher;
    self.dialogOpenInProgress = false;
    self.init = init;

    function openPatient (item) {
      $state.go('patient.overview', {id: item.id});
    }
    
    function addNewPatient(){
      if (!self.dialogOpenInProgress) {
        self.dialogOpenInProgress = true;

        ngDialog.open({
          template: routeService.patient_create,
          controller: 'profileModalController',
          cache: false,
          resolve: {
            activeTab: function(){
              return "tab_about"
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
      $log.info('Patients list controller initiated.');
      self.initDialogOpenWatcher();
    }

    self.init();

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageSidebarClosed = false;

  }

})();