/**
 * Created by Alsheuski Alexei on 10/02/16.
 * File: services.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.services', [
      'KindCannApp.services.authorizationService',
      'KindCannApp.services.commonService',
      'KindCannApp.services.historyService',
      'KindCannApp.services.prescriptionsService',
    ])
})();