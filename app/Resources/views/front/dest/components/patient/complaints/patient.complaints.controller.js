/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: patient.complaints.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.patientComplaints', [])
        .controller('patientComplaintsController', patientComplaintsController);

    patientComplaintsController.$inject = ['$state', 'ngDialog', '$log', 'routeService', '$rootScope', '$window'];

    function patientComplaintsController($state, ngDialog, $log, routeService, $rootScope, $window) {

        var self = this;
        self.inProgress = false;
        self.additionParams = {
            patientId: $state.params.id
        };

        self.addComplaint = addComplaint;
        self.open = open;
        self.print = print;
        self.initDialogOpenWatcher = initDialogOpenWatcher;
        
        function open(item){
            var dialogPromise = ngDialog.open({
                template: routeService.patient_complaint_show + '?id=' + item.id,
                controller: 'complaintModalController',
                controllerAs: 'modalCtrl',
                resolve: {
                    complaintId: function(){
                        return item.id;
                    },
                    isCreate: function () {
                        return false;
                    }
                }
            });
        }

        /**
         * Create and open creating new complaint dialog window
         */
        function addComplaint() {

            if (!self.inProgress) {
                self.inProgress = true;

                var dialogPromise = ngDialog.open({
                    template: routeService.patient_complaint_create,
                    controller: 'complaintModalController',
                    controllerAs: 'modalCtrl',
                    resolve: {
                        complaintId: function(){
                            return "";
                        },
                        isCreate: function () {
                            return true;
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
                self.inProgress = false;
            });
        }

        self.initDialogOpenWatcher();

    }

})();
