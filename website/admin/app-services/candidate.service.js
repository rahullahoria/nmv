/**
 * Created by spider-ninja on 6/4/16.
 */
(function () {
    'use strict';

    angular
        .module('app')
        .factory('CandidateService', CandidateService);

    CandidateService.$inject = ['$http'];

    function CandidateService($http) {
        var service = {};

        service.GetProducts = GetProducts;
        service.Create = Create;
        service.Update = Update;
        service.Delete = Delete;
        service.GetUserInstance = GetUserInstance;
        service.UpdateInstance = UpdateInstance;
        service.SearchUser = SearchUser;
        service.StartTest = StartTest;
        service.GetOrders = GetOrders;
        service.SubmitRespnse = SubmitRespnse;
        service.ShowResults = ShowResults;
        service.GetTopicMatter = GetTopicMatter;
        service.CheckOTP = CheckOTP;
        service.StartDemoTest = StartDemoTest;
        service.CreateOrder = CreateOrder;
        service.GetExamSubjects = GetExamSubjects;
        service.GetExamSubjectTopics = GetExamSubjectTopics;
        service.AddRemark = AddRemark;
        service.GetRemarks = GetRemarks;
        service.SendSMS = SendSMS;
        service.upload = upload;
        service.GetInventory = GetInventory;
        service.GetCountries = GetCountries;
        service.GetCountriesStates = GetCountriesStates;
        service.GetCountriesStatesCities = GetCountriesStatesCities;


        return service;

        ///channel/sms/:mobile/text/:text

        function SearchUser(mobile) {
            return $http
                .get('http://api.nmv.shatkonlabs.com/users/'+mobile+'/search')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetCountries() {
            return $http
                .get('http://api.nmv.shatkonlabs.com/country')
                .then(handleSuccess, handleError('Error getting all country'));
        }

        function GetCountriesStates(country) {
            return $http
                .get('http://api.nmv.shatkonlabs.com/country/'+country+'/states')
                .then(handleSuccess, handleError('Error getting all country'));
        }

        function GetCountriesStatesCities(country,state) {
            return $http
                .get('http://api.nmv.shatkonlabs.com/country/'+country+'/states/'+state+'/cities')
                .then(handleSuccess, handleError('Error getting all country'));
        }

        function SendSMS(mobile,text) {
            return $http.post('https://api.examhans.com/channel/sms/'+mobile, {'text':text})
                .then(handleSuccess, handleError('Error updating user'));
;
        }

        function upload(id) {
            var fileUrl = document.getElementById(id);
            var data = new FormData();
            data.append('fileToUpload', fileUrl.files[0]);
            return $http.post("http://api.file-dog.shatkonlabs.com/files/rahul", data, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined} }).then(handleSuccess, handleError('Error uploading file'));

        }

        function GetExamSubjects(id) {
            return $http
                .get('https://api.examhans.com/exams/'+id+'/subjects')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetExamSubjectTopics(id,subjectId) {
            return $http
                .get('https://api.examhans.com/exams/'+id+'/subjects/'+subjectId+'/topics')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetTopicMatter(topicId) {
            return $http
                .get('https://api.examhans.com/topics/'+topicId+'/videos')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function ShowResults(userMD5,testId) {
            return $http
                .get('https://api.examhans.com/user/'+userMD5+'/test/'+testId+'/result')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetUserInstance(professionId,uType,month) {
            return $http
                .get('https://api.bulldog.shatkonlabs.com/profession/'+professionId+'/type/'+uType+"/instance?month=" +month)
                .then(handleSuccess, handleError('Error getting all users'));
        }





        function GetOrders(filter) {
            return $http.get('http://api.nmv.shatkonlabs.com/order'+filter).then(handleSuccess, handleError('Error getting user by id'));
        }



        function GetProducts() {
            return $http.get('http://api.nmv.shatkonlabs.com/products' ).then(handleSuccess, handleError('Error getting user by username'));
        }

        function GetInventory() {
            return $http.get('http://api.nmv.shatkonlabs.com/inventory' ).then(handleSuccess, handleError('Error getting user by username'));
        }

        function CheckOTP(userMd5,type,otp) {
            return $http.get('https://api.examhans.com/user/'+userMd5+'/verify/'+type+'/otp/'+otp ).then(handleSuccess, handleError('Error getting user by username'));
        }

        function Create(user) {
            return $http.post('http://api.nmv.shatkonlabs.com/users', user).then(handleSuccess, handleError('Error creating user'));
        }

        function Update(user) {
            return $http.put('https://api.shatkonjobs.com/candidates/' + user.id, user).then(handleSuccess, handleError('Error updating user'));
        }

        function UpdateInstance(instance) {
            return $http.post('https://api.bulldog.shatkonlabs.com/instance', instance).then(handleSuccess, handleError('Error updating user'));
        }

        function SubmitRespnse(userMd5,testId,responseId,instance) {
            ///user/:userMd5/test/:testId/question/:responseId
            return $http.post('https://api.examhans.com/user/'+userMd5+'/test/'+testId+'/question/'+responseId, instance).then(handleSuccess, handleError('Error updating user'));
        }

        function StartTest(userMd5, instance) {
            return $http.post('https://api.examhans.com/user/'+userMd5+'/test/', instance).then(handleSuccess, handleError('Error updating user'));
        }

        function CreateOrder(order) {
            return $http.post('http://api.nmv.shatkonlabs.com/order', order).then(handleSuccess, handleError('Error updating user'));
        }

        function AddRemark(userMd5, instance) {

            return $http.post('https://api.wazir.shatkonlabs.com/feedbacks/1/nmv-'+userMd5, instance).then(handleSuccess, handleError('Error updating user'));
        }

        function GetRemarks(userMd5) {
            return $http
                .get('https://api.wazir.shatkonlabs.com/feedbacks/1/nmv-'+userMd5)
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function StartDemoTest(userMd5) {
            return $http.post('https://api.examhans.com/user/'+userMd5+'/demo_test', {}).then(handleSuccess, handleError('Error updating user'));
        }

        function Delete(id) {
            return $http.delete('/api/users/' + id).then(handleSuccess, handleError('Error deleting user'));
        }

        // private functions

        function handleSuccess(res) {
            return res.data;
        }

        function handleError(error) {
            return function () {
                return { success: false, message: error };
            };
        }
    }

})();
