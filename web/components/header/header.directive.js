/**
 * Created by Alsheuski Alexei on 12/02/16.
 * File: header.directive.js
 */

(function () {
  'use strict';

  angular
    .module('KindCannApp.components.header.directive', [])
    .directive('kindCannHeader', kindCannHeader);

  kindCannHeader.$inject = ['$log'];

  function kindCannHeader($log){
    return {
      restrict: 'AE',
      scope: {},
      link: link
    };

    function link (scope, el, attr){

      $log.info('Header directive initiated.');

      //TODO: code below from metronic template, rewrite it later
      // Handles the horizontal menu
      var handleHorizontalMenu = function () {
        //handle tab click
        $('.page-header').on('click', '.hor-menu a[data-toggle="tab"]', function (e) {
          e.preventDefault();
          var nav = $(".hor-menu .nav");
          var active_link = nav.find('li.current');
          $('li.active', active_link).removeClass("active");
          $('.selected', active_link).remove();
          var new_link = $(this).parents('li').last();
          new_link.addClass("current");
          new_link.find("a:first").append('<span class="selected"></span>');
        });

        // handle search box expand/collapse
        $('.page-header').on('click', '.search-form', function (e) {
          $(this).addClass("open");
          $(this).find('.form-control').focus();

          $('.page-header .search-form .form-control').on('blur', function (e) {
            $(this).closest('.search-form').removeClass("open");
            $(this).unbind("blur");
          });
        });

        // handle hor menu search form on enter press
        $('.page-header').on('keypress', '.hor-menu .search-form .form-control', function (e) {
          if (e.which == 13) {
            $(this).closest('.search-form').submit();
            return false;
          }
        });

        // handle header search button click
        $('.page-header').on('mousedown', '.search-form.open .submit', function (e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).closest('.search-form').submit();
        });


      };

      handleHorizontalMenu();
    }
  }

})();