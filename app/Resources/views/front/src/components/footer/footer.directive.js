/**
 * Created by Alsheuski Alexei on 04/03/16.
 * File: footer.directive.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.footer', [])
      .directive('kcFooterComponent', kcFooterComponent);

  kcFooterComponent.$inject = ['$log'];

  function kcFooterComponent($log){
    return {
      restrict: 'A',
      scope: {},
      controller: controller,
      controllerAs: 'footerCtrl'
    };

    function controller(){

      var self = this;
      self.init = init;

      function init(){
        $log.info('Footer successfully inited.');
        Layout.initFooter(); // init footer
      }

      self.init();

    }
  }



})();