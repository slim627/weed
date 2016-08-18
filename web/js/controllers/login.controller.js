/**
 * Created by Alsheuski Alexei on 09/02/16.
 * File: LoginController.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.controllers.login', [])
    .controller('LoginController', LoginController);

  //LoginController.$inject = ['$rootScope', '$state', 'authorizationService', '$log'];

  function LoginController ($rootScope, $state, authorizationService, $log){

    var self = this;
    self.formData = {};
    self.isLoginFormActive = true;
    self.authorization = authorizationService;

    self.doLogin = doLogin;
    self.resetPassword = resetPassword;

    function doLogin (path) {
      self.authorization.login({
        path: path,
        formData: self.formData
      });
    }

    function resetPassword (path){
      self.authorization.resetPassword({
        path: path,
        formData: self.formData
      })
    }

    // set sidebar closed and body solid layout mode
    $rootScope.settings.layout.pageSidebarClosed = false;

  }

})();

