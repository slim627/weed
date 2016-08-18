/**
 * Created by Alsheuski Alexei on 01/03/16.
 * File: header.service.js
 */

(function () {
  'use strict';

  angular
      .module('KindCannApp.components.header.service', [])
      .service('headerService', headerService)
      .service('headerCacheService', headerCacheService);

  headerService.$inject = ['$http', '$log', '$q', 'routeService', 'headerCacheService'];

  function headerService($http, $log, $q, routeService, headerCacheService){

    var self = this;
    self.notesOnHeader = [];
    self.getData = getData;
    self.getHeaderNotes = getHeaderNotes;
    self.storeHeaderNotes = storeHeaderNotes;
    self.init = init;

    function getData(){

      var def = $q.dever();
      var queryString = {
        method: 'POST',
        url: routeService.notification_list_data
      };

      //TODO: implement get notification data from server

      return def.promise;
    }


    function getHeaderNotes(){
      self.notesOnHeader = headerCacheService.get('headerNotesCache');
    }

    function storeHeaderNotes(data){
      $log.info(data);
      self.notesOnHeader = data;
      headerCacheService.put('headerNotesCache', data);
    }

    function init(){
      self.getHeaderNotes();
    }

    self.init();

  }

  headerCacheService.$inject = ['CacheFactory'];

  function headerCacheService(CacheFactory){

    var headerNotesCache;

    if(!CacheFactory.get('headerNotesCache')){
      headerNotesCache = CacheFactory('headerNotesCache', {
        maxAge: 7 * 24 * 60 * 60 * 1000,
        deleteOnExpire: 'aggressive',
        storageMode: 'localStorage'
      });
    }
    else{
      headerNotesCache = CacheFactory.get('headerNotesCache');
    }

    return headerNotesCache;

  }


})();