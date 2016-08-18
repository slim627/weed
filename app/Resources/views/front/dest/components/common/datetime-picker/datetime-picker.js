/**
 * Created by Alsheuski Alexei on 22/02/16.
 * File: date-time-picker.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.components.datetimePicker', [])
        .directive('kcDatetimePicker', kcDatetimePicker);

    kcDatetimePicker.$inject = [];

    function kcDatetimePicker(){
        return {
            restrict: 'A',
            scope: {},
            link: link
        };

        function link(scope, el, attr){

            function initDatetimePicker(){

                var $el = angular.element(el);

                $el.datetimepicker({
                    autoclose: true,
                    format: $el.find('input').attr('format'),
                    pickerPosition: "bottom-left"
                });

                //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
            }

            function init(){
                initDatetimePicker();
            }

            init();


            return self;
        }
    }

})();