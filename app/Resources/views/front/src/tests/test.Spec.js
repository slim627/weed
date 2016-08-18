/**
 * Created by Alsheuski Alexei on 12/02/16.
 * File: test.Spec.js
 */

(function () {
  'use strict';

  describe("LoginController", function() {

    beforeEach(module('KindCannApp'));

    var controller, authorizationService;

    beforeEach(inject(function ($controller, _authorizationService_) {
      authorizationService = _authorizationService_;
      var $scope = {};
      controller = $controller('LoginController', {$scope: $scope});
    }));

    describe('Initialization', function () {
      it('Should isLoginFormActive be a true', function () {
        expect($scope.isLoginFormActive).toEqual(true);
      })
    });


  });


})();