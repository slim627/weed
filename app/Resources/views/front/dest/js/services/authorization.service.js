/**
 * Created by Alsheuski Alexei on 11/02/16.
 * File: authorization.service.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.services.authorizationService', [])
    .service('authorizationService', authorizationService)
    .service('currentUserCacheService', currentUserCacheService);

  authorizationService.$inject = ['$http', '$q', '$log', '$state', 'routeService', 'toaster', '$rootScope', 'headerService', 'currentUserCacheService'];

  function authorizationService($http, $q, $log, $state, routeService, toaster, $rootScope, headerService, currentUserCacheService){

    var self = this;
    self.inProgress = false;
    self.currentUser = {};
    self.login = login;
    self.goToLogin = goToLogin;
    self.logout = logout;
    self.resetPassword = resetPassword;
    self.restoreCurrentUser = restoreCurrentUser;


    function restoreCurrentUser(){
      if(!self.currentUser.name){
        self.currentUser = currentUserCacheService.get('currentUserCache');
      }
    }

    self.restoreCurrentUser();


    /**
     * Main authorization method
     * @param {Object} params - data for XHR request
     * @param {string} params.path - login request url
     * @param {Object} params.formData - form fields data object
     * @param {string} params.formData.username - username
     * @param {string} params.formData.password - password
     * @param {string} params.formData.csrfToken - csrf from security token
     * @returns {d.promise|*|promise}
     */
    function login(params){

      if(!self.inProgress){
        self.inProgress = true;

        var def = $q.defer();

        $http({
          method: 'POST',
          url: params.path,
          data: {
            _username: params.formData.username,
            _password: params.formData.password,
            _csrf_token: params.formData.csrfToken
          }
        })
          .then(onSuccess, onError)
          .finally(function () {
            self.inProgress = false;
          });

        return def.promise;
      }

      function onSuccess(res){
        console.log( res );
        if(res.status == 200){
          self.currentUser = res.data.response.user;
          currentUserCacheService.put('currentUserCache', self.currentUser);
          headerService.storeHeaderNotes(res.data.response.notifications);
          $state.go('dashboard');
        }
      }

      function onError(err){
        $log.error('Login submit error!', err);
      }

    }


    /**
     * Reset password method
     * @param {Object} params - data for XHR request
     * @param {string} params.path - reset password request url
     * @param {Object} params.formData - form fields data object
     * @param {string} params.formData.usernameOrEmail - usernameOrEmail
     */
    function resetPassword(params){

      if(!self.inProgress){
        self.inProgress = true;

        var def = $q.defer();

        $http({
          method: 'POST',
          url: params.path,
          data: {
            _usernameOrEmail: params.formData.usernameOrEmail
          }
        })
          .then(onSuccess, onError)
          .finally(function () {
            self.inProgress = false;
          });

        return def.promise;
      }

      function onSuccess(res){
        console.log( res );
        //$state.go('page.dashboard');
      }

      function onError(err){
        $log.error('Reset password submit error!', err);
      }

    }
    
    function goToLogin(){
      $log.info('Go to login');
      $state.go('login');
    }

    /**
     * Logout method
     * @returns {*}
     */
    function logout(){

      if(!self.inProgress){
        self.inProgress = true;

        var def = $q.defer();

        $http({
          method: 'GET',
          url: routeService.logout
        })
          .then(onSuccess, onError)
          .finally(function () {
            self.inProgress = false;
          });

        return def.promise;
      }

      function onSuccess(res){
        $log.info('Logout success.');
        $state.go('login');
      }

      function onError(err){
        $log.error('Logout error!', err);
      }

    }


    return self;

  }

  currentUserCacheService.$inject = ['CacheFactory'];

  function currentUserCacheService(CacheFactory){

    var currentUserCache;

    if(!CacheFactory.get('currentUserCache')){
      currentUserCache = CacheFactory('currentUserCache', {
        maxAge: 7 * 24 * 60 * 60 * 1000,
        deleteOnExpire: 'aggressive',
        storageMode: 'localStorage'
      });
    }
    else{
      currentUserCache = CacheFactory.get('currentUserCache');
    }

    return currentUserCache;

  }

})();