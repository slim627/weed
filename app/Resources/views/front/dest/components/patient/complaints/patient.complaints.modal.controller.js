/**
 * Created by Alsheuski Alex on 17/02/16.
 * File: patient.complaints.modal.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.complaintModal', [])
        .controller('complaintModalController', complaintModalController);

    complaintModalController.$inject = [
        '$scope',
        '$http',
        'commonService',
        '$log',
        'toaster',
        'isCreate',
        'complaintsService',
        'complaintId',
        'ngDialog',
        'routeService'
    ];

    function complaintModalController(
        $scope,
        $http,
        commonService,
        $log,
        toaster,
        isCreate,
        complaintsService,
        complaintId,
        ngDialog,
        routeService
    ){

        var self = this;
        self.formName = "";
        self.tasksList = [];
        self.inProgress = false;
        self.fields = {};
        self.editTask = editTask;
        self.addTask = addTask;

        function addTask(){

            var dialogPromise = ngDialog.open({
                template: routeService.patient_note_create + '?complaintId=' + complaintId,
                controller: 'notesModalController',
                controllerAs: 'modalCtrl',
                resolve: {
                    isEdit: function(){
                        return false;
                    }
                }
            });
        }
        
        function editTask(taskId){
            var dialogPromise = ngDialog.open({
                template: routeService.patient_note_edit + '?id=' + taskId,
                controller: 'notesModalController',
                controllerAs: 'modalCtrl',
                resolve: {
                    isEdit: function(){
                        return true;
                    }
                }
            });
        }

      /**
       * if isCreate is false, then we getting tasks for existing complaints
       */
        if(!isCreate){
            complaintsService
                .getTasksListForComplaint({ complaintId: complaintId})
                .then(function (res) {
                    self.tasksList = res.response;
                })
        }

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
