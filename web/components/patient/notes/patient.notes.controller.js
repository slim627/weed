/**
 * Created by Alsheuski Alex on 26/02/16.
 * File: patient.notes.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.patientNotes', [])
        .controller('patientNotesController', patientNotesController);

    patientNotesController.$inject = ['$state', 'ngDialog', '$log', 'routeService', '$rootScope', '$window'];

    function patientNotesController($state, ngDialog, $log, routeService, $rootScope, $window) {

        var self = this;
        self.dialogInProgress = false;
        self.additionParams = {
            patientId: $state.params.id
        };

        self.addNote = addNote;
        self.openNote = openNote;
        self.print = print;
        self.initDialogOpenWatcher = initDialogOpenWatcher;

        function openNote(item){
            var dialogPromise = ngDialog.open({
                template: routeService.patient_note_show + '?id=' + item.id,
                controller: 'notesModalController',
                controllerAs: 'modalCtrl',
                resolve: {
                    isEdit: function(){
                        return false;
                    }
                }
            });
        }

        /**
         * Create and open creating new note dialog window
         */
        function addNote() {

            if (!self.dialogInProgress) {
                self.dialogInProgress = true;

                var dialogPromise = ngDialog.open({
                    template: routeService.patient_note_create,
                    controller: 'notesModalController',
                    controllerAs: 'modalCtrl',
                    resolve: {
                        isEdit: function(){
                            return false;
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
