﻿'use strict';
expCircleApp.controller('KnowledgeListController', ['$scope','$http', function ($scope,$http) {
 $scope.WSLink = "WS/WS.php?";
    // init main page model
	 $scope.Knowledges=[];
    $scope.xxx = [
        {
            Id: "0",
            Title: "bug1",
            Description: "afhsdhvhs",
            Steps: [{ Id: 12, StepType: 1, StepContent: "" }],
            Tags: [ 'Bug','ffff']
        },
        {
            Id: "2",
            Title: "bug2",
            Description: "khskdvhksdhvikshd",
            Steps: [{ Id: 13, StepType: 2, StepContent: "" }],
            Tags: ['Bug', 'ffff']
        },
        {
            Id: "3",
            Title: "bug3",
            Description: "HIHIHIHIHIHI",
            Steps: [{ Id: 14, StepType: 3, StepContent: "" }],
            Tags: ['Bug', 'ffff']
        }
    ];

    $scope.GetKnowledges =function(){  $http.get(  $scope.WSLink+"WS=GetKnowledges"   ).then(function(res){
	   
	   $scope.Knowledges= JSON.parse(res.data);
	}) }; 
 $scope.GetKnowledges();	
}]);