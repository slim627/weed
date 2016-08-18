/**
 * Created by Alsheuski Alexei on 16/02/16.
 * File: sidebar.directive.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.sidebar', [])
    .directive('kcSidebarComponent', kcSidebarComponent);

  function kcSidebarComponent(){
    return {
      restrict: 'A',
      scope: {},
      link: link
    };

    function link(scope, el, attr){

      Layout.initSidebar();

    }
  }

})();