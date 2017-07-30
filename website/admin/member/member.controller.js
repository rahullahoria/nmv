﻿(function () {
    'use strict';

    angular
        .module('app')
        .controller('MemberController', MemberController);

    MemberController.$inject = ['UserService', '$cookieStore', 'CandidateService', '$rootScope', 'FlashService','$location'];
    function MemberController(UserService, $cookieStore, CandidateService,  $rootScope, FlashService,$location) {
        var vm = this;

        vm.user = {};
        vm.adv = {};
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;

        vm.champs = 0;
        vm.good = 0;
        vm.improve = 0;
        vm.bad = 0;
        vm.tuserComments = [];

        vm.successFilter = true;
        vm.dangerFilter = true;
        vm.warningFilter = true;
        vm.primaryFilter = true;

        vm.threeMonths = [];
        vm.whichMonth = {};
        vm.loadUser = loadUser;
        vm.currentMonthIndex = 0;
        vm.dataLoading = false;
        vm.subjectTotalQ = 0;

        vm.currentShow = 0;

        //nd/d/p
        vm.loadType = ($location.search().t != undefined)?$location.search().t:'nd';

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

            //loadMonths();
            loadUser();


        }


        vm.searchUser = function (){
            console.log(vm.tsUser.mobile,"mobile");
            if(vm.tsUser.mobile){
                vm.userRemarks(vm.tsUser.mobile);
            CandidateService.SearchUser(vm.tsUser.mobile).then(function(response){
                vm.tUser = response.response[0];
                vm.tsUser.mobile = '';
                console.log(vm.tUser,response);


            });
            }
        }


        vm.writeAboutUser = function(){

            CandidateService.AddRemark(vm.tUser.mobile,
                {
                    "feedback":vm.tUser.feedback + ' by '+ vm.inUser.username,
                    "digieye_user_id":1
                }
                )
                .then(function (response) {
                    response.feedback.creation = "Just Now";
                    vm.tuserComments.push(response.feedback);
                    vm.tUser.feedback = '';
                });

        };

        vm.userRemarks = function(mobile){

            CandidateService.GetRemarks(mobile)
                .then(function (response) {
                    vm.tuserComments = response.feedbacks;
                    vm.tuserComments.reverse();
                    console.log('inside controller',vm.tuserComments);
                });

        };

        vm.uploadVideo = function(){
            vm.videoUploading = true;
            vm.processing = true;

            CandidateService.upload('video_file').then(function (response) {
                vm.processing = false;
                if (response.file.id) {
                    vm.adv.video_id = response.file.id;
                    console.log(vm.adv.video_id);

                } else {
                    FlashService.Error(response.message);
                    vm.videoUploading = false;

                }

                return false;

            });
        }

        vm.adv.days = 0;
        vm.adv.stores = 0;

        vm.logout = function(){
            vm.inUser = null;
            UserService.DeleteInUser();
            $location.path('#/login');
        };

        function loadUser(){
            vm.inUser = UserService.GetInUser();
            if(!vm.inUser.name)
                $location.path('/login');
            console.log("in user",vm.inUser);


        }






        vm.newAddModel = function(){
            /*vm.loadUserId = index;
            CandidateService.GetRemarks(vm.users[vm.loadUserId].mobile)
                .then(function (response) {
                    vm.comments = response.feedbacks;




                    console.log('inside controller',vm.comments);
                });*/
            $("#adsModel").modal("show");
        };



        vm.createAdv = function(mobile,id){


            CandidateService.CreateAdv(vm.adv).then(function (response) {
                if(response.ads.id){
                    vm.adv = {};
                    vm.adv.file = {};
                }
                console.log(response);
            });

        }









        vm.date1 = new Date().getDate();
        vm.getFun = function(work){
           return Math.floor((Math.random() * (work/60/60)) + (work/60/60/4));
        };

        vm.videoDetails = function(index){
            vm.loadUserId = index;
            /*CandidateService.GetRemarks(vm.users[vm.loadUserId].mobile)
                .then(function (response) {
                    vm.comments = response.feedbacks;




                    console.log('inside controller',vm.comments);
                });*/

            var myVideo = document.getElementsByTagName('video')[0];

            var vidURL = "http://api.file-dog.shatkonlabs.com/files/rahul/"+vm.allAds[index].vedio_id;
            myVideo.src = vidURL;
            myVideo.load();
            myVideo.play();
            $("#userModel").modal("show");
        };





        function loadAllUsers() {
            UserService.GetAll()
                .then(function (users) {
                    vm.allUsers = users;
                });
        }

        function deleteUser(id) {
            UserService.Delete(id)
            .then(function () {
                loadAllUsers();
            });
        }





    }

})();