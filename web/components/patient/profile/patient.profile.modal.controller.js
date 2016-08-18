/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: patient.profile.modal.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.profileModal', [])
        .controller('profileModalController', profileModalController);

    profileModalController.$inject = [
        '$scope',
        '$http',
        'commonService',
        '$log',
        'toaster',
        'activeTab',
        'routeService',
        'patientsService'
    ];

    function profileModalController(
        $scope,
        $http,
        commonService,
        $log,
        toaster,
        activeTab,
        routeService,
        patientsService
    ){

        var self = this;
        self.formName = "";
        self.inProgress = false;
        self.fields = {};
        self.removeFile = removeFile;
        self.removeUploadedFile = removeUploadedFile;
        self.activeTab = activeTab;

        self.closeThisDialog = function () {
            $scope.closeThisDialog();
        };

        function removeUploadedFile(fileIndex){


            if(!self['removeBtn_' + fileIndex + '_progress']){
                self['removeBtn_' + fileIndex + '_progress'] = true;
                $http({
                    method: 'POST',
                    url: routeService.patient_remove_file,
                    params: {
                        id: self.uploadedFiles[fileIndex].id
                    }
                }).then(onSuccess, onError);
            }


            function onSuccess(res){
                $log.info("File successfully removed.");
                toaster.pop({
                    type: 'success',
                    title: 'Success',
                    body: 'File successfully removed!',
                    showCloseButton: true,
                    bodyOutputType: 'trustedHtml'
                });
                self.uploadedFiles.splice(fileIndex, 1);
                patientsService.currentPatient.uploadedFiles.splice(fileIndex, 1);
                self['removeBtn_' + fileIndex + '_progress'] = false;
            }

            function onError(err){
                $log.error("File deleting error.", err);
            }
        }

        function removeFile(index){
            self.fields.files.splice(index, 1);
        }

        /**
         * if form fiend exists in scope and serverMessage not empty
         * then clear it on change field value
         * @param fieldName
         */
        self.onchange = function(fieldName){

            if($scope[self.formName].hasOwnProperty(fieldName)){
                if( $scope[self.formName][fieldName].$error.serverMessage ){
                    $scope[self.formName][fieldName].$error.serverMessage = "";
                }
            }
        };

        self.onSubmit = function (url) {

            self.form = $scope[self.formName];

            if(!self.inProgress){
                self.inProgress = true;

                commonService.sendFormData({
                    url: url,
                    formName: self.formName,
                    fields: self.fields
                })
                .then(onSuccess, onError)
                .finally(function () {
                    self.inProgress = false;
                });

            }

            function onSuccess(res){
                toaster.pop({
                    type: 'success',
                    title: 'Success',
                    body: 'New record was successfully added!',
                    showCloseButton: true,
                    bodyOutputType: 'trustedHtml'
                });

                $scope.closeThisDialog(res);
            }

            /**
             * If error then set server error message for form fileds
             * @param err
             */
            function onError(err){
                $log.error("Submitting form error!", err.data.response.data);
                for(var error in err.data.response.data){
                    self.form[self.formName + '[' + error + ']' ].$error.serverMessage = err.data.response.data[error];
                }
            }

        }

    }


})();
