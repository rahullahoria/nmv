(function () {
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
            loadToCallCandidates();

        }



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




        vm.userDetails = function(index){
            vm.loadUserId = index;
            CandidateService.GetRemarks(vm.users[vm.loadUserId].mobile)
                .then(function (response) {
                    vm.comments = response.feedbacks;




                    console.log('inside controller',vm.comments);
                });
            $("#userModel").modal("show");
        };

        vm.newAddModel = function(){
            /*vm.loadUserId = index;
            CandidateService.GetRemarks(vm.users[vm.loadUserId].mobile)
                .then(function (response) {
                    vm.comments = response.feedbacks;




                    console.log('inside controller',vm.comments);
                });*/
            $("#adsModel").modal("show");
        };

        vm.writeAboutUser = function(user){

            CandidateService.AddRemark(user.username,
                {
                    "feedback":vm.user.feedback,
                    "digieye_user_id":1
                }
                )
                .then(function (response) {
                    vm.user.feedback = '';
                });

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

        vm.inviteForTest = function(mobile){
            var text = "Hi! You have not completed your demo test.\nEarn Your 150Rs Now.\n";
            text += "https://examhans.com/members/#/?rt=demo";

            CandidateService.SendSMS(mobile,text).then(function (response) {
                alert("SMS sent: "+text);
                vm.user.feedback = 'SMS sent as : ' + text;
                vm.writeAboutUser({username:mobile});
            });

        }

        vm.askToBuy = function(mobile,index){
            var text = "Congratulation!\nFor Earning Rs."+vm.users[index].amount_made+" on Examhans.com\nKeep learning and earning\n Buy Our Premium Plans\nCheck @\n";
            text += "https://examhans.com/#plans";

            CandidateService.SendSMS(mobile,text).then(function (response) {
                alert("SMS sent: "+text);
                vm.user.feedback = 'SMS sent as : ' + text;
                vm.writeAboutUser({username:mobile});
            });

        }

        vm.askForRef = function(mobile){
            var text = "Hi!\nShare Demo Link bellow with your friends\nGet Free Silver Membership Plan\n";
            text += "https://examhans.com/members/#/?rt=demo&ref_user="+mobile;

            CandidateService.SendSMS(mobile,text).then(function (response) {
                alert("SMS sent: "+text);
                vm.user.feedback = 'SMS sent as : ' + text;
                vm.writeAboutUser({username:mobile});
            });

        }




        vm.loadToCallCandidates = loadToCallCandidates;

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



        function loadToCallCandidates(){
            vm.dataLoading = true;

            CandidateService.GetStatus()
                .then(function (response) {
                    vm.allAds = response.ads;
                    vm.dataLoading = false;




                    console.log('inside controller',vm.users);
                });

        }

        /*function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user;
                });
        }*/

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