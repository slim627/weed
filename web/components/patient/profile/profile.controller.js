(function () {
  'use strict';

  angular
      .module('KindCannApp.patient.profile.profileController', [])
      .controller('patientProfileController', patientProfileController);

  patientProfileController.$inject = [
    '$rootScope',
    '$scope',
    'patientsService',
    '$state',
    '$log',
    'ngDialog',
    'routeService',
    '$controller'
  ];

  function patientProfileController(
      $rootScope,
      $scope,
      patientsService,
      $state,
      $log,
      ngDialog,
      routeService,
      $controller
  ) {

    //TODO: rewrite this block
    $scope.$on('$viewContentLoaded', function () {
      App.initAjax(); // initialize core modules
      //Layout.setSidebarMenuActiveLink('set', $('#sidebar_menu_link_profile')); // set profile link active in sidebar menu
    });

    var self = this;
    self.dataSource = patientsService;
    self.currentState = $state;
    self.changePatientVerifiedStatus = changePatientVerifiedStatus;
    self.editPatient = editPatient;
    self.initDialogOpenWatcher = initDialogOpenWatcher;
    self.openDialogButton = '';
    self.init = init;


    /**
     * Changing patient verify status
     * @param isVerified
     */
    function changePatientVerifiedStatus(isVerified) {
      self.dataSource.changePatientVerifyStatus({verifiedStatus: isVerified})
    }

    /**
     * Open edit current patient dialog
     */
    function editPatient(params) {
      self.openDialogButton = params.openDialogButton;

      if (!self['openButton_' + params.openDialogButton]) {
        self['openButton_' + params.openDialogButton] = true;

        var dialog = ngDialog.open({
          template: routeService.patient_edit + '?patientId=' + self.dataSource.currentPatient.id,
          controller: 'profileModalController',
          cache: false,
          resolve: {
            activeTab: function(){
              return params.activeTab
            }
          },
          controllerAs: 'modalCtrl'
        });


        dialog.closePromise.then(function (res) {
          try{
            self.dataSource.currentPatient = res.value.data.response;
          }catch(err){
            $log.error('Error!', err);
          }
        });
      }
    }

    /**
     * Init watching on open dialog event
     */
    function initDialogOpenWatcher() {
      $rootScope.$on('ngDialog.opened', function (e, $dialog) {
        $log.info('ngDialog opened: ' + $dialog.attr('id'));
        self['openButton_' + self.openDialogButton]  = false;
      });
    }


    function init() {
      $log.info('User profile controller was initiated.');

      patientsService.getPatientData({
        id: $state.params.id
      });

      self.initDialogOpenWatcher();
    }

    self.init();

    $rootScope.settings.layout.pageSidebarClosed = false;
  }

})();