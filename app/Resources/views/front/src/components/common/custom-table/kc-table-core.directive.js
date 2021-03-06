/**
 * Created by Alsheuski Alexei on 15/02/16.
 * File: table.directive.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.components.customTable.tableCore', [])
    .directive('kindCannTable', kindCannTable);

  kindCannTable.$inject = ['$state', '$injector', '$rootScope'];

  function kindCannTable($state, $injector, $rootScope){
    return {
      restrict: 'A',
      scope: {},
      transclude: true,
      templateUrl: './components/common/custom-table/tpl/table.html',
      bindToController: {
        datasource: '@',
        rowClickHandler: '=',
        tableAdditionParams: '=',
        doUpdateTable: '='
      },
      controllerAs: 'tableCtrl',
      controller: controller,
      link: link
    };

    function controller($scope){

      var self = this;
      self.items = [];
      self.headRow = [];
      self.totalRowCount = 0;
      self.rowsOnPage = '10';
      self.searchString = "";
      self.currentPage = 1;
      self.openPage = openPage;
      self.onChangeRowsOnPage = onChangeRowsOnPage;
      self.searchStringChanged = searchStringChanged;

      // Inject needed data service
      self.dataSource = $injector.get(self.datasource);


      function handleTableUpdateTrigger(){
        $rootScope.$watch(function(){
          return self.doUpdateTable;
          }, function (newVal) {

            self.currentPage = 1;
            getData();

        })
      }

      function getData(){

        var queryParams = {
          limit: self.rowsOnPage,
          page: self.currentPage,
          filter: $.extend( {}, { searchString: self.searchString }, self.tableAdditionParams )
        };


        //Getting data
        self.dataSource
          .getData(queryParams)
          .then(function (res) {
            self.items = res.response.rows;
            self.headRow = res.response.head;
            self.totalRowCount = parseInt(res.response.count);
          });
      }

      function openPage(page){
        self.currentPage = page;
        getData();
      }
      
      function onChangeRowsOnPage(){
        self.currentPage = 1;
        getData();
      }
      
      function searchStringChanged(){
        self.currentPage = 1;
        getData();
      }

      function init(){
        getData();
        handleTableUpdateTrigger();
      }

      init();

    }

    function link(scope, el, attr, controller){

    }
  }

})();