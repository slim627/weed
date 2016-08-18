/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: patient.prescription.modal.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.prescriptionModal', [])
        .controller('prescriptionModalController', prescriptionModalController);

    prescriptionModalController.$inject = ['$scope', '$http', 'commonService', '$log', 'toaster', 'activeTab'];

    function prescriptionModalController($scope, $http, commonService, $log, toaster, activeTab){

        var self = this;
        self.formName = "";
        self.inProgress = false;
        self.fields = {};
        self.removeFile = removeFile;
        self.activeTab = activeTab;

        self.closeThisDialog = function () {
            $scope.closeThisDialog();
        };

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
