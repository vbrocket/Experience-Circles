
var expCircleApp = angular.module('expCircleApp', ['ngRoute','ngTagsInput']); // 'ngAnimate'

expCircleApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when("/Home", {
        controller: "HomeController",
        templateUrl: "/Views/Home.html"
    }).when("/AddKnowledge", {
        controller: "AddKnowledgeController",
        templateUrl: "/Views/AddKnowledge.html"
    }).when("/KnowledgeList", {
        controller: "KnowledgeListController",
        templateUrl: "/Views/KnowledgeList.html"
        }).when("/ReadKnowledge/:KnowledgeID", {
        controller: "ReadKnowledgeController",
        templateUrl: "/Views/ReadKnowledge.html"
        }).otherwise({ redirectTo: "/Home" });

}]);
