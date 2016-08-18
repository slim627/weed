/**
 * Created by Alsheuski Alexei on 19/02/16.
 * File: common.service.js
 */

(function () {
    'use strict';

    angular
        .module('KindCannApp.services.commonService', [])
        .service('commonService', commonService);

    commonService.$inject = ['$q', '$http', '$log'];

    function commonService($q, $http, $log){

        var self = this;
        self.sendFormData = sendFormData;

        /**
         * Create formatted object with form fields data for sending to server like a submit form request
         * @param {Object} params
         * @param {string} params.formName - form name
         * @param {Object} params.fields - object with form fields
         * @returns {{}}
         */
        function sendFormData(params){

            var fd = new FormData();
            
            $log.info(params.fields);

            for(var index in params.fields){

                if(params.fields.hasOwnProperty(index)){

                    switch (index){
                        case 'id':
                            // if field name is 'id' then adding this filed in form data just like id without form name
                            fd.append(index, params.fields[index] || '');
                            break;

                        case 'files':
                            // if form contain file or multiple files then create file field's array
                            for(var i = 0; i < params.fields[index].length; i++){
                                fd.append(index + '[' + i + ']', params.fields[index][i]);
                            }
                            break;

                        default:
                            // adding filed in form data with form name, for example 'patient[name]'
                            if(params.fields[index] === undefined || params.fields[index] === null){
                                fd.append(index, '');
                            }else{
                                fd.append(index, params.fields[index]);

                            }

                    }

                }
            }

            return $http({
                method: 'POST',
                url: params.url,
                data: fd,
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            });

        }


        return self;
    }

})();