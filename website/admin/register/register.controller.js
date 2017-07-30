(function () {
    'use strict';

    angular
        .module('app')
        .controller('RegisterController', RegisterController);

    RegisterController.$inject = ['UserService', '$location', '$rootScope', 'FlashService','CandidateService'];
    function RegisterController(UserService, $location, $rootScope, FlashService,CandidateService) {
        var vm = this;

        vm.register = register;

        vm.reg = function(){
            vm.dataLoadingReg = true;
            CandidateService.Create(vm.user
                )
                .then(function (response) {
                    console.log("resp",response);
                    vm.dataLoadingReg = false;

                    if (response.response && response.response.id) {
                        vm.regSuccess = true;
                        //alert("Thanks for register! Enjoy your Free Wifi :)");
                        /*AuthenticationService.SetCredentials(vm.user.reg_username, vm.user.reg_password);
                        vm.inUser = response.results;
                        vm.inUser.username = vm.inUser.reg_username;
                        $cookieStore.put('inUser', JSON.stringify(vm.inUser));
                        vm.dataLoadingReg = false;

                        vm.showVerification = true;

                        console.log("auth success in user",vm.inUser);
                        //$location.path('/member');*/

                    } else {
                        FlashService.Error(response.error.text);
                        vm.regError = response.error.text;

                    }
                });

            console.log(vm.user);
        };

        function register() {
            vm.dataLoading = true;
            UserService.Create(vm.user)
                .then(function (response) {
                    if (response.success) {
                        FlashService.Success('Registration successful', true);
                        $location.path('/login');
                    } else {
                        FlashService.Error(response.message);
                        vm.dataLoading = false;
                    }
                });
        }
    }

})();
