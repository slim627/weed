/**
 * Created by Alsheuski Alexei on 15/02/16.
 * File: table.paginator.directive.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.components.customTable.tablePagination', [])
    .directive('kcTablePaginator', kcTablePaginator);

  function kcTablePaginator(){
    return {
      restrict: 'A',
      require: '^kindCannTable',
      scope: {},
      templateUrl: '../assets/tpl/table.pagination.html',
      link: link
    };

    function link(scope, el, attr, tableCtrl){

      scope.firstShowingRecordNumber = 0;
      scope.lastShowingRecordNumber = 0;

      /**
       * Watch by totalRowCount in parent directive controller
       * and update max page count in paginator
       */
      function watchByTotalRowCount(){
        scope.$watch(function () {
          return tableCtrl.totalRowCount;
        }, function (newVal) {
          scope.totalRowCount = newVal;
          scope.maxPages = calcMaxPagesCount(newVal, tableCtrl.rowsOnPage);
        });
      }

      /**
       * Watch by current page in parent directive controller
       * and update current page in markup
       */
      function watchByCurrentPage(){
        scope.$watch(function () {
          return tableCtrl.currentPage;
        }, function (newVal) {

          scope.currentPage = newVal;
        });
      }

      /**
       * Watching by displayed rows and render info about it
       */
      function watchByTableRows(){
        scope.$watch(function () {
          return tableCtrl.items;
        }, function (newVal) {
          scope.firstShowingRecordNumber = ( (tableCtrl.currentPage - 1) * tableCtrl.rowsOnPage) + 1;
          scope.lastShowingRecordNumber = scope.firstShowingRecordNumber +  newVal.length - 1;
        })
      }

      /**
       * Watch by max rows on page filter value
       * and update man pade count in markup
       */
      function watchByMaxRowsOnPage(){
        scope.$watch(function () {
          return tableCtrl.rowsOnPage;
        }, function (newVal) {
          scope.maxPages = calcMaxPagesCount(tableCtrl.totalRowCount, newVal);
        })
      }

      /**
       * Helper function to calculate max pages count
       * @param totalRows
       * @param rowsOnPage
       * @returns {number}
       */
      function calcMaxPagesCount(totalRows, rowsOnPage){

        totalRows = parseInt(totalRows);
        rowsOnPage = parseInt(rowsOnPage);
        
        if(totalRows <= rowsOnPage){
          return 1;
        }else if(totalRows % rowsOnPage === 0){
          return totalRows / rowsOnPage;
        }else{
          return Math.floor(( totalRows / rowsOnPage )) + 1;
        }
      }

      /**
       * Loading next table page
       */
      scope.prevPage = function (){
        if(scope.currentPage > 1){
          scope.currentPage--;
          tableCtrl.openPage(scope.currentPage);
        }
      };

      /**
       * Loading previous table page
       */
      scope.nextPage = function (){
        if(scope.currentPage < scope.maxPages){
          scope.currentPage++;
          tableCtrl.openPage(scope.currentPage);
        }
      };

      /**
       * Init watchers
       */
      function init(){
        watchByTotalRowCount();
        watchByCurrentPage();
        watchByMaxRowsOnPage();
        watchByTableRows();
      }

      init();
    }
  }


})();