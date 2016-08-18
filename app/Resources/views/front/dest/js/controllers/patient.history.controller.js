/**
 * Created by Alsheuski Alex on 26/02/16.
 * File: patient.history.controller.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.controllers.patientHistory', [])
        .controller('patientHistoryController', patientHistoryController);

    patientHistoryController.$inject = ['$state', 'ngDialog', '$log', 'routeService', '$rootScope', '$window'];

    function patientHistoryController($state, ngDialog, $log, routeService, $rootScope, $window) {

        var self = this;
        self.additionParams = {
            patientId: $state.params.id
        };

        self.print = print;


        function print(url){
            $window.open(url, '_blank');
            $log.info(url);
        }


    }

})();
