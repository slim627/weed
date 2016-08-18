/**
 * Created by Alsheuski Alexei on 12/02/16.
 * File: head.controller.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.components.header.controller', [])
    .controller('headController', headController);

  headController.$inject = ['$rootScope', '$log', 'authorizationService', 'headerService'];

  function headController($rootScope, $log, authorizationService, headerService){

    $log.info('Header controller initiated.');

    var self = this;
    self.doLogout = doLogout;
    self.dataSource = headerService;
    self.currentUser = authorizationService.currentUser;

    function doLogout(){
      authorizationService.logout();
    }
  }

})();