var latLong;
$.getJSON("http://ipinfo.io", function(ipinfo){
    console.log("Found location ["+ipinfo.loc+"] by ipinfo.io");
    latLong = ipinfo.loc.split(",");
});

(function () {
  var app = angular.module('fb', ['ngAnimate']);

  app.controller('FbCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.FAVORITE_DATA = "";
    $scope.activeTab = document.querySelectorAll("li.active")[0].childNodes[0].id;
    $scope.favoriteDatas = [];
    $scope.fbKeyword = "";
    $scope.latitude = "0";
    $scope.longitude = "0";
    $scope.name = "FB Search";
    $scope.detailName = "";
    $scope.detailPictureUrl = "";
    $scope.detailId = "";
    $scope.hashTag = "#";
    $scope.access_token = "access_token=EAADNAoDNw5wBAGXQrmwKXOpMhJT0kPnjd6pUYHdD8AiDk317wchZAvo5G4bWH2H8LG5elSLhXY4EdH7SnI94ZCtFdjOZAoybyfZCc3CIKmLz8VFAebc95W5Nzp3NoYwP1lO5zKaspclyvMp6h7ERtt1ZAqyuVDEcAFZA1NdD9CgAZDZD";
    $scope.preAddress = "https://graph.facebook.com/v2.8/";
    $scope.surAddress = "/picture?width=700&heith=700&" + $scope.access_token;
    $scope.nextOffset = 0;
    $scope.prevOffset = 0;
    $scope.offSet = 0;

    $scope.back = function(){
      $scope.activeDetailTab = false;
      $scope.activeUserTab = $scope.activeTable[0];
      $scope.activePageTab = $scope.activeTable[1];
      $scope.activeEventTab = $scope.activeTable[2];
      $scope.activePlaceTab = $scope.activeTable[3];
      $scope.activeGroupTab = $scope.activeTable[4];
      $scope.activeFavoriteTab = $scope.activeTable[5];
    }

    $scope.prev = function(fbKeyword, type, offset){
      $scope.offSet = offset;
      $scope.PROCESSING = true;
      if(type == "place")
          fbUrl = 'http://csci571-hw8-163622.appspot.com/?keyword=' + $scope.fbKeyword + "&type="+type+"&latitude="+$scope.latitude+"&longitude="+$scope.longitude + "&offset=" +$scope.offSet;
      else
          fbUrl = 'http://csci571-hw8-163622.appspot.com/?keyword=' + $scope.fbKeyword + "&type="+type + "&offset=" +$scope.offSet;

      $http({
        method: 'GET',
        url: fbUrl,
      }).then(function successCallback(response) {
        $scope.PROCESSING = false;
          $scope.data = response.data.data;
          $scope.paging = response.data.paging;
          if(typeof($scope.paging.previous) == "undefined")
            $scope.showPrevBtn = false;
          else{
            $scope.showPrevBtn = true;
          }
          if(typeof($scope.paging.next) == "undefined")
            $scope.showNextBtn = false;
          else{
            $scope.showNextBtn = true;
          }
      }, function errorCallback(response) {
        $scope.data = "sth went wrong";
        $scope.paging = "sth went wrong";
        // error
      });

      if(type=="user") $scope.showUser();
      if(type=="page") $scope.showPage();
      if(type=="event") $scope.showEvent();
      if(type=="place") $scope.showPlace();
      if(type=="group") $scope.showGroup();
      if(type=="favorite") $scope.showFavorite();

    }

    $scope.next = function(fbKeyword, type, offset){
      $scope.offSet = offset;
      $scope.PROCESSING = true;
      if(type == "place")
          fbUrl = 'http://csci571-hw8-163622.appspot.com/?keyword=' + $scope.fbKeyword + "&type="+type+"&latitude="+$scope.latitude+"&longitude="+$scope.longitude + "&offset=" +$scope.offSet;
      else
          fbUrl = 'http://csci571-hw8-163622.appspot.com/?keyword=' + $scope.fbKeyword + "&type="+type + "&offset=" +$scope.offSet;
      $http({
        method: 'GET',
        url: fbUrl,
      }).then(function successCallback(response) {
        $scope.PROCESSING = false;
          $scope.data = response.data.data;
          $scope.paging = response.data.paging;
          if(type == "place")
            $scope.offSet = $scope.paging.cursors.after;
          if(typeof($scope.paging.previous) == "undefined"){
            $scope.showPrevBtn = false;
          }
          else{
            $scope.showPrevBtn = true;
          }
          if(typeof($scope.paging.next) == "undefined"){
            $scope.showNextBtn = false;
          }
          else{
            $scope.showNextBtn = true;
          }
      }, function errorCallback(response) {
        $scope.data = "sth went wrong";
        $scope.paging = "sth went wrong";
        // error

      });

      if(type=="user") $scope.showUser();
      if(type=="page") $scope.showPage();
      if(type=="event") $scope.showEvent();
      if(type=="place") $scope.showPlace();
      if(type=="group") $scope.showGroup();
      if(type=="favorite") $scope.showFavorite();
    }

    $scope.fbSearch = function(fbKeyword, type, offset){
      $scope.offSet = 0;
      $scope.activeTab = type;
      $scope.latitude = latLong[0];
      $scope.longitude = latLong[1];

      if(typeof(fbKeyword) == "undefined")
        $scope.fbKeyword = "";
      else
        $scope.fbKeyword = fbKeyword;

      if($scope.fbKeyword.length != 0){
        $scope.activeUserTab = false;
        $scope.activePageTab = false;
        $scope.activeEventTab = false;
        $scope.activePlaceTab = false;
        $scope.activeGroupTab = false;
        $scope.activeFavoriteTab = false;
        $scope.activeDetailTab = false;
        $scope.PROCESSING = true;
        $scope.data = [];
        $scope.paging = [];
        if(type == "place")
          fbUrl = 'http://csci571-hw8-163622.appspot.com/?keyword=' + $scope.fbKeyword + "&type="+type+"&latitude="+$scope.latitude+"&longitude="+$scope.longitude + "&offset=" +$scope.offSet;
        else
          fbUrl = 'http://csci571-hw8-163622.appspot.com/?keyword=' + $scope.fbKeyword + "&type="+type + "&offset=" +$scope.offSet;

        $http({
          method: 'GET',
          url: fbUrl
        }).then(function successCallback(response) {
          $scope.PROCESSING = false;
          $scope.data = response.data.data;
          $scope.paging = response.data.paging;
          if(type == "place"){
            $scope.showNextBtn = false;
              if(typeof($scope.paging) != "undefined"){
                $scope.offSet = $scope.paging.cursors.after;
                $scope.showNextBtn = true;
              }
          }
          else{
            if(typeof($scope.paging.previous) == "undefined"){
              $scope.showPrevBtn = false;
            }
            else{
              $scope.showPrevBtn = true;
            }
            if(typeof($scope.paging.next) == "undefined"){
              $scope.showNextBtn = false;
            }
            else{
              $scope.showNextBtn = true;
            }
          }

          if(type=="user") $scope.showUser();
          if(type=="page") $scope.showPage();
          if(type=="event") $scope.showEvent();
          if(type=="place") $scope.showPlace();
          if(type=="group") $scope.showGroup();
          if(type=="favorite") $scope.showFavorite();
        }, function errorCallback(response) {

          $scope.data = "sth went wrong";
          $scope.paging = "sth went wrong";
          // error
          alert(3);
        });
        // empty form
        //$scope.fbKeyword = "";

      }
    }

    $scope.detail = function(url, name, type, id){
      fbUrl = 'http://csci571-hw8-163622.appspot.com/?id=' + id
      $scope.PROCESSING = false;
      $scope.PROCESSING_DETAIL = true;
      $scope.detailName = name;
      $scope.detailPictureUrl = url;
      $scope.detailId = id;
      $scope.detailType = type;
      $scope.detailInfo = [[$scope.detailPictureUrl, $scope.detailName, $scope.detailType, $scope.detailId]];
      $scope.albums = [];
      $scope.posts = [];
      $scope.activeAlbumPenal = false;
      $scope.activePostPenal = false;
      $http({
        method: 'GET',
        url: fbUrl,
      }).then(function successCallback(response) {
        $scope.PROCESSING_DETAIL = false;
        $scope.albums = response.data.albums.data;
        $scope.posts = response.data.posts.data;
        if($scope.albums.length == 0)
          $scope.activeAlbumPenal = false;
        else
          $scope.activeAlbumPenal = true;
        if($scope.posts.length == 0)
          $scope.activePostPenal = false;
        else
          $scope.activePostPenal = true;
      }, function errorCallback(response) {
        $scope.PROCESSING_DETAIL = false;
        $scope.albums = "sth went wrong";
        $scope.posts = "sth went wrong";
        // error
      });

      $scope.activeTable = [$scope.activeUserTab,
                            $scope.activePageTab,
                            $scope.activeEventTab,
                            $scope.activePlaceTab,
                            $scope.activeGroupTab,
                            $scope.activeFavoriteTab]

      $scope.showDetail();
    }

    $scope.saveFavorite = function(url, name, type, id){
      $scope.favoriteDatas.push([url, name, type, id]);
      $scope.saveToLocalStorage($scope.favoriteDatas);
    }

    $scope.deleteFavorite = function(id){
      var idx = $scope.favoriteDatas.findIndex( function (item) {
        return item[3] === id;
      })

      if (idx > -1) {
        $scope.favoriteDatas.splice(idx, 1);
        $scope.saveToLocalStorage($scope.favoriteDatas);
      }
    }

    $scope.checkFavorite = function(id){
      var idx = $scope.favoriteDatas.findIndex( function (item) {
        return item[3] === id;
      })
      if (idx > -1)
        return true;
      else
        return false;
    }

    $scope.share = function (url, name) {
           FB.init({
             appId: '1477987718878067',
             status: true,
             cookie: true,
             xfbml: true,
             version: 'v2.8'
           });
           FB.ui({
              app_id:'1477987718878067',
              method:'feed',
              link:window.location.href,
              picture:url,
              name:name,
              caption: 'FB SEARCH FROM USC CSCI571',
            }, function(response){
              if (response && ! response.error_message) {
                alert("Posted Successfully");
              }
              else {
                alert("Not Posted");
              }
            });
          }


    $scope.clear = function(){
      $scope.nextOffset = 0;
      $scope.prevOffset = 0;
      $scope.data = [];
      $scope.paging = [];
      $scope.albums = [];
      $scope.posts = [];
      $scope.PROCESSING = false;
      $scope.activeUserTab = false;
      $scope.activePageTab = false;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = false;
      $scope.fbKeyword = "";
    }
    $scope.showUser = function(){
      $scope.activeUserTab = true;
      $scope.activePageTab = false;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = false;
    }

    $scope.showPage = function(){
      $scope.activeUserTab = false;
      $scope.activePageTab = true;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = false;
    }

    $scope.showEvent = function(){
      $scope.activeUserTab = false;
      $scope.activePageTab = false;
      $scope.activeEventTab = true;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = false;
    }

    $scope.showPlace = function(){
      $scope.activeUserTab = false;
      $scope.activePageTab = false;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = true;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = false;
    }

    $scope.showGroup = function(){
      $scope.activeUserTab = false;
      $scope.activePageTab = false;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = true;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = false;
    }

    $scope.showFavorite = function(){
      $scope.favoriteDatas = $scope.getToLocalStorage();
      $scope.activeUserTab = false;
      $scope.activePageTab = false;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = true;
      $scope.activeDetailTab = false;
    }

    $scope.showDetail = function(){
      $scope.activeUserTab = false;
      $scope.activePageTab = false;
      $scope.activeEventTab = false;
      $scope.activePlaceTab = false;
      $scope.activeGroupTab = false;
      $scope.activeFavoriteTab = false;
      $scope.activeDetailTab = true;
    }

    $scope.saveToLocalStorage = function(data){
      localStorage.setItem($scope.FAVORITE_DATA, JSON.stringify(data));
    }

    $scope.getToLocalStorage = function(){
      return JSON.parse(localStorage.getItem($scope.FAVORITE_DATA)) || [];
    }

  }]);

})();