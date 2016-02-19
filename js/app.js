(function(){
'use strict';
angular.module('shareinsights', ['ngMaterial', 'ngCookies', 'ngRoute'])
.config(['$mdThemingProvider', function($mdThemingProvider) {
  $mdThemingProvider.theme('default')
    .primaryPalette('deep-purple')
    .accentPalette('deep-orange');
  $mdThemingProvider.theme('myGreen')
    .primaryPalette('deep-orange')
    .accentPalette('orange');
}
]).config(configFunc).run(runFunc);

configFunc.$inject = ['$routeProvider'];
function configFunc($routeProvider){
  $routeProvider.
    when('/', {
      templateUrl: 'tpls/dash.html',
      controller: 'ShareController',
      controllerAs: 'vm'
    }).
    when('/login', {
      templateUrl: 'tpls/login.html',
      controller: 'Login',
      controllerAs: 'vm'
    }).
    otherwise({rediredtTo: '/login'});
}

runFunc.$inject = ['$cookies', '$location'];
function runFunc($cookies, $location){
  var us = $cookies.getObject('user');
  if(!us){
    $location.path('/login');
  }
}

/*angular.module('shareinsights').controller('ShareController', [
  '$scope', '$http',
  function($scope, $http){
    $scope.imgPath = './images/';
    $http.get('./api/users.json', function(response){
      $scope.users = response.data;
    }, function(resp){
      console.log(resp.data);
    });
  }
]);*/

angular.module('shareinsights').controller('Login', Login);
Login.$inject = ['$http', '$cookies', '$filter', '$location'];
function Login($http, $cookies, $filter, $location){
  var vm = this;
  vm.authenticate = auth;
  vm.error = {};
  function auth(){
    if(vm.sso){
      vm.load = true;
      $http.get('api/users.json').then(
            function(response){
              vm.user = $filter('filter')(response.data, {'sso': vm.sso})[0];
              if(vm.user){
                $cookies.putObject('user', vm.user);
                $location.path('/');
              } else {
                vm.error.message = "SSO not present in the meeting LIST!";
              }
            }, function(error){

            }
          );
    }
  }
}


angular.module('shareinsights').controller('ShareController', ShareCtrl);
ShareCtrl.$inject = ['$http','$cookies', '$filter', '$mdSidenav', '$mdMedia'];

function ShareCtrl($http, $cookies, $filter, $mdSidenav, $mdMedia){
  var vm = this;
  vm.users = [];
  vm.user = $cookies.getObject('user');

  vm.meeting = {};
  if(vm.user.meeting == "mycon"){
    vm.meeting.title = "Water MyConnections";
    vm.meeting.subtitle = "Group Mentoring program Closing Event";
  } else {
    vm.meeting.title = "GE Water MEA";
    vm.meeting.subtitle = "Sales Meeting";
  }


  loadAll();
  vm.imgPath= './images/';
  vm.results = results;
  vm.finds = finds;
  vm.openSide = buildToggler;
  vm.newInsight = {
    category: 'Continue',
  };
  vm.savenew = saveNew;
  vm.insights = [];
  vm.insights = getAllInsights();
  vm.showall = show;
  vm.goHome = function(){
    vm.listed = false;
    vm.new = false;
  }
  vm.counter = counter;
  function counter(sso){
    if(vm.insights.length > 0)
      return $filter('filter')(vm.insights, {'toSSO':sso}).length;
    else
      return [];
  }
  vm.home = home();
  function home(){
    vm.listed = false;
    vm.new = false;
  }
  function show(navID, it){
    vm.listed = true;
    vm.new = false;
    $mdSidenav(navID).toggle();
    vm.for = it.name;
    showInsights(it);
  }
  function showInsights(it){
    vm.owninsights = $filter('filter')(vm.insights, {"toSSO":it.sso});
  }
  function getAllInsights(){
    var u = vm.user.meeting;
    return $http.get('api/insights_' + u + '.json', {headers: {'Content-Type': 'application/json', 'Cache-Control' : 'no-cache'}}).then(
      function(response){
        vm.insights = response.data;
        vm.insightCounter = response.data.length;
        return vm.insights;
      },
      function(error){
        console.log(error);
        return false;
      }
    );
  }
  function saveNew(newInsight){
      newInsight.date = new Date().getTime();
      newInsight.from = vm.user;
      newInsight.toSSO = newInsight.to.sso;
      var req = {
				method: "POST",
				data: {"meeting": vm.user.meeting, "insight": newInsight},
				url: "api/insert.php",
				headers: {'Content-Type': 'application/x-www-form-urlencoded', 'Cache-Control' : 'no-cache'}
			}
			return $http(req).then(
				function(response){
          vm.insights.push(newInsight);
          showInsights(response.data.to);
          vm.newInsight = null;
          vm.newInsight = {
            category:'Continue'
          };
          vm.listed = true;
          vm.new = false;
          vm.for = response.data.to.name;
				}
			).catch(function(error){
        console.log(error);
      });
    }
  vm.initNew = initNew;
  function initNew(navID, it){
    vm.new=true;
    vm.listed = false;
    vm.newInsight.to = it;
    $mdSidenav(navID).toggle();
  }
  function buildToggler(navID) {
    $mdSidenav(navID).toggle();
  }

  function finds(query){
    if(query)
      return $filter('filter')(vm.users, {'name':query, 'meeting': vm.user.meeting});
    else
      return $filter('filter')(vm.users, {'sso':!100000000});
  }

  function results(query){
    if(query)
      return $filter('filter')(vm.users, {'name':query, 'meeting': vm.user.meeting});
    else
      return $filter('filter')(vm.users, {'meeting': vm.user.meeting});
  }

  function createFilterFor(query){
    return function filterIn(item){
      return ( item.value.indexOf(query) === 0 );
    }
  }

  function loadAll() {
    return $http.get('api/users.json').then(
      function(response){
        vm.users = response.data;
        return vm.users;
      }, function(error){
        console.log(error);
        return false;
      }
    );
  }
}
})();