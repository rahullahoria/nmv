﻿(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', , 'ui.select', 'ngCookies', 'datatables','timer','base64','ui.bootstrap.datetimepicker','dndLists'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$locationProvider'];
    function config($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                controller: 'LoginController',
                templateUrl: 'login/login.view.html',
                controllerAs: 'vm'
                
            })

            .when('/login', {
                controller: 'LoginController',
                templateUrl: 'login/login.view.html',
                controllerAs: 'vm'
            })

            .when('/register', {
                controller: 'RegisterController',
                templateUrl: 'register/register.view.html',
                controllerAs: 'vm'

            })



            .when('/home', {
                controller: 'HomeController',
                templateUrl: 'home/home.view.html',
                controllerAs: 'vm'
                
            })

            .when('/member', {
                controller: 'MemberController',
                templateUrl: 'member/member.view.html',
                controllerAs: 'vm'

            })

            .when('/orders', {
                controller: 'OrderController',
                templateUrl: 'orders/order.view.html',
                controllerAs: 'vm'

            })

            .when('/products', {
                controller: 'ProductController',
                templateUrl: 'products/product.view.html',
                controllerAs: 'vm'

            })

            .when('/inventory', {
                controller: 'InventoryController',
                templateUrl: 'inventory/inventory.view.html',
                controllerAs: 'vm'

            })

            .when('/prepare/:topic_name/:topic_id', {
                controller: 'PrepareController',
                templateUrl: 'prepare/prepare.view.html',
                controllerAs: 'vm'

            })



            .otherwise({ redirectTo: '/' });
    }

    run.$inject = ['$rootScope', '$location', '$cookieStore', '$http'];
    function run($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in and trying to access a restricted page
            var restrictedPage = $.inArray($location.path(), ['/login', '/register']) === -1;
            var loggedIn = $rootScope.globals.currentUser;
            if (restrictedPage && !loggedIn) {
                $location.path('/');
            }
        });
    }

})();