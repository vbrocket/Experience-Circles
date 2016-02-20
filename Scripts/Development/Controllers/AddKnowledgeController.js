'use strict';
expCircleApp.controller('AddKnowledgeController', ['$scope', function ($scope) {

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
        Title: "",
        Description: "",
        Steps: [{ Id: $scope.getGUID(), StepType: 1, StepContent: "" }],
        Tags: [{"text":'Bug'}]
    };
    
    // Add New Step
    $scope.AddNewStep = function (curStep) {
        var step = { };
        if (curStep) {
            step = { Id: $scope.getGUID(), StepType: curStep.StepType, StepContent: "" };
        }
        else
        {
            step = { Id: $scope.getGUID(), StepType: 1, StepContent: "" };
        }
        $scope.Knowledge.Steps.push(step);
    }

    // Remove Step
    $scope.RemoveNewStep = function (curStep) {
        for (var i = 0; i < $scope.Knowledge.Steps.length; i++) {
            if($scope.Knowledge.Steps[i].Id == curStep.Id)
            {
                $scope.Knowledge.Steps.splice(i, 1);
                return;
            }
        }
    }

    // Set Step Type
    $scope.SetStepType = function (curStep, stepType) {
        curStep.StepType = stepType;
    }
    

}]);