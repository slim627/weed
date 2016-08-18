/**
 * Created by Alsheuski Alex on 26/02/16.
 * File: patient.prescriptions.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.patientPrescriptions', [])
        .controller('patientPrescriptionsController', patientPrescriptionsController);

    patientPrescriptionsController.$inject = ['$state', 'ngDialog', '$log', 'routeService', '$rootScope', '$window'];

    function patientPrescriptionsController($state, ngDialog, $log, routeService, $rootScope, $window) {

        var self = this;
        self.dialogInProgress = false;
        self.additionParams = {
            patientId: $state.params.id
        };

        self.add = add;
        self.open = open;
        self.print = print;
        self.initDialogOpenWatcher = initDialogOpenWatcher;

        function open(item){
            var dialogPromise = ngDialog.open({
                template: routeService.patient_prescriptions_edit + '?prescriptionId=' + item.id,
                controller: 'prescriptionModalController',
                controllerAs: 'modalCtrl',
                cache: false,
                resolve: {
                    activeTab: function(){
                        return ""
                    }
                }
            });
        }

        /**
         * New prescription dialog window
         */
        function add() {

            if (!self.dialogInProgress) {
                self.dialogInProgress = true;

                var dialogPromise = ngDialog.open({
                    template: routeService.patient_prescriptions_create,
                    controller: 'prescriptionModalController',
                    controllerAs: 'modalCtrl',
                    cache: false,
                    resolve: {
                        activeTab: function(){
                            return ""
                        }
                    }
                });
            }
        }
        
        function print(url){
            $window.open(url, '_blank');
            $log.info(url);
        }

        function initDialogOpenWatcher() {
            $rootScope.$on('ngDialog.opened', function (e, $dialog) {
                $log.info('ngDialog opened: ' + $dialog.attr('id'));
                self.dialogInProgress = false;
            });
        }

        self.initDialogOpenWatcher();

    }

})();
