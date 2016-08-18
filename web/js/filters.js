/**
 * Created by Alsheuski Alexei on 28/02/16.
 * File: filters.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.filters', [])
      .filter('kcFileSize', kcFileSize);

      function kcFileSize () {
          return function(input){
            return Math.floor(input / 1024) + 'kb';
          }
      }

})();