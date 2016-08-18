/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: patient.notes.modal.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.notesModal', [])
        .controller('notesModalController', notesModalController);

    notesModalController.$inject = ['$scope', '$http', 'commonService', '$log', 'toaster', 'isEdit'];

    function notesModalController($scope, $http, commonService, $log, toaster, isEdit){

        var self = this;
        self.formName = "";
        self.inProgress = false;
        self.isEdit = isEdit;
        self.fields = {};
        self.closeThisDialog = function () {
            $scope.closeThisDialog();
        };


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

                var message = "";

                if(self.isEdit){
                    message = 'New record was successfully updated!'
                }else{
                    message = 'New record was successfully added!'
                }

                toaster.pop({
                    type: 'success',
                    title: 'Success',
                    body: message,
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
