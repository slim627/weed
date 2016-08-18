/**
 * Created by Alsheuski Alexei on 09/02/16.
 * File: doctors.directive.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.doctors.doctorsDirective', [])
    .directive('kcDoctorsComponent', kcDoctorsComponent);

  kcDoctorsComponent.$inject = [
    '$rootScope',
    '$log',
    'ngDialog',
    'routeService'
  ];

  function kcDoctorsComponent (
      $rootScope,
      $log,
      ngDialog,
      routeService
  ){

    return{
      restrict: 'A',
      controller: controller,
      controllerAs: 'doctorsCtrl',
      link: link
    };

    function controller(){

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

    function link(scope, el, attr){

    }

  }

})();