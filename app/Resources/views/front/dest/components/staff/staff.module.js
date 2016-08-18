/**
 * Created by Alsheuski Alexei on 02/03/16.
 * File: staff.module.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.components.staff', [
          'KindCannApp.staff.staffService',
          'KindCannApp.staff.staffController',
          'KindCannApp.staff.staffModal',
          'KindCannApp.component.accessLevel'
      ])


})();