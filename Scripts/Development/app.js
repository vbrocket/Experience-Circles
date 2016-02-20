
var expCircleApp = angular.module('expCircleApp', ['ngRoute']); // 'ngAnimate'

expCircleApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when("/Home", {
        controller: "HomeController",
        templateUrl: "/Views/Home.html"
    }).when("/AddKnowledge", {
        controller: "AddKnowledgeController",
        templateUrl: "/Views/AddKnowledge.html"
    }).otherwise({ redirectTo: "/Home" });

}]);
