'use strict';
expCircleApp.controller('ReadKnowledgeController', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

    // Create GUID to use with server side syncing
    $scope.getGUID = function () {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
              .toString(16)
              .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
          s4() + '-' + s4() + s4() + s4();
    }

    // init main page model
    $scope.Knowledge = {
        Id: 0,
        Title: "This is a test knowledge title",
        Description: "My knowledge description goes here",
        Steps: [{ Id: $scope.getGUID(), StepType: 1, StepContent: "my Text" }, { Id: $scope.getGUID(), StepType: 2, StepContent: "<html></html>" }, { Id: $scope.getGUID(), StepType: 3, StepContent: "http://uaksf21ce9f5.ashrafelazoomy4.koding.io/WS/img/B15BAA26-6A64-4FA7-AFD6-51A0F439FFFA.png" }, { Id: $scope.getGUID(), StepType: 4, StepContent: "http://uaksf21ce9f5.ashrafelazoomy4.koding.io/WS/img/B15BAA26-6A64-4FA7-AFD6-51A0F439FFFA.png" }],
        Tags: ['Bug', 'Java' , 'MVC']
    };





}]);