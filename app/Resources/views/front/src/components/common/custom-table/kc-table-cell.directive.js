/**
 * Created by Alsheuski Alexei on 27/02/16.
 * File: kc-table-cell.directive.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.components.customTable.tableCell', [])
      .directive('kcTableCell', kcTableCell);

  kcTableCell.$inject = ['$filter'];

  function kcTableCell($filter) {
    return {
      restrict: 'A',
      require: '^kindCannTable',
      scope: {
        kcTableCellPosition: '@',
        kcTableCellType: '=',
        kcTableCellData: '='
      },
      template: "<span ng-bind='value' ng-class='class'></span>",
      link: link
    };

    function link(scope, el, attr, tableCtrl) {

      function watchByTableItems() {
        scope.$watch(function () {
          return tableCtrl.items;
        }, function (newVal) {
          handleCell();
        });
      }

      function handleCell() {
        if (scope.kcTableCellPosition === 'header') {

          if (scope.kcTableCellType === 'hidden') {
            el.attr('hidden', 'true');
          }
          scope.value = scope.kcTableCellData;

        } else {

          switch (scope.kcTableCellType) {
            case 'hidden':
              el.attr('hidden', 'true');
              scope.value = scope.kcTableCellData;
              break;

            case 'string':
              scope.value = scope.kcTableCellData;
              break;

            case 'bool':
              if (scope.kcTableCellData == true) {
                scope.value = "Yes";
                scope.class = 'label label-success';
              } else {
                scope.value = "No";
                scope.class = 'label label-danger';
              }
              break;

            case 'date':
              var value = parseInt(scope.kcTableCellData);
              if (value !== '' || value !== undefined) {
                scope.value = $filter('date')(scope.kcTableCellData * 1000, 'yyyy-MM-dd');
              }
              break;

            case 'datetime':
              var value = parseInt(scope.kcTableCellData);
              if (value !== '' || value !== undefined) {
                scope.value = $filter('date')(scope.kcTableCellData * 1000, 'yyyy-MM-dd HH:mm');
              }
              break;

            default:
              scope.value = scope.kcTableCellData;
          }

        }
      }

      function init() {
        watchByTableItems();
      }

      init();

    }
  }

})();