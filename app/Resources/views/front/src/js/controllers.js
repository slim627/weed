/**
 * Created by Alsheuski Alexei on 12/02/16.
 * File: controllers.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.controllers', [
      'KindCannApp.controllers.dashboard',
      'KindCannApp.controllers.login',
      'KindCannApp.controllers.patientDialogController',
      'KindCannApp.controllers.patientHistory',
      'KindCannApp.controllers.patientPrescriptions',
      'KindCannApp.controllers.prescriptionModal',
    ]);

})();