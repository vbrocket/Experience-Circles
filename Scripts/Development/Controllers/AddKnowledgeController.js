'use strict';
expCircleApp.controller('AddKnowledgeController', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {

  $scope.WSLink = "WS/WS.php?";
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
        KnowledgeGUID: $scope.getGUID(),
        Title: "",
        Description: "",
        Steps: [{ Id: $scope.getGUID(), StepType: 1, StepContent: "" }],
        Tags: [{"text":'Bug',"TagID":0,   "IsSystemTag":false}],
		IsSolved:true
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

    // Remove Step
    $scope.SetStepContent = function (Id, content) {
        for (var i = 0; i < $scope.Knowledge.Steps.length; i++) {
            if ($scope.Knowledge.Steps[i].Id == Id) {
                $scope.Knowledge.Steps[i].StepContent = content;
                return;
            }
        }
    }

    // Set Step Type
    $scope.SetStepType = function (curStep, stepType) {
        curStep.StepType = stepType;
    }
    $scope.baseImageURL = "WS/";
    $scope.OnTextFieldPaste = function (event , curStep) {
        //console.log(e);
        var clipData = event.clipboardData;
        angular.forEach(clipData.items, function (item, key) {
            if (clipData.items[key]['type'].match(/image.*/)) {
                curStep.StepType = 3;
                // if it is a image
                var img = clipData.items[key].getAsFile();
                console.log('A image sized ' + img.size + ' is being uploaded.');
                var fd = new FormData();
                fd.append('file', img);
                // CHANGE /post/paste TO YOUR OWN FILE RECEIVER
                $http.post("WS/WSSaveImage.php", fd, {
                    transformRequest: angular.identity,
                    headers: {
                        'Content-Type': undefined
                    }
                }).success(function (url) {
                    curStep.StepContent = $scope.baseImageURL + url.trim();
                    //var timer = $timeout(function () {
                    //    $timeout.cancel(timer);
                    //    $scope.SetStepContent(curStep.Id, url.trim());
                    //}, 1);
                    
                    //$scope.body = $scope.body + '\n![PICTURE](' + url + ')';
                    // the url returns
                }).error(function (data) {
                    alert(data);
                });
            }
            else {
                var text = event.clipboardData.getData('text/plain');
                //detect if it's link
                if ($scope.validateURL(text))
                {
                    curStep.StepType = 4;
                }
                
            }
        });

        if(curStep.StepContent == "")
            $scope.AddNewStep();
    };

    // check URL
    $scope.validateURL = function (link) {
        if (link && link.trim().length != 0) {
            var regexp = /((ftp|https?):\/\/)?(www\.)?[a-z0-9\-\.]{3,}\.[a-z]{2,6}(:[0-9]{1,5})?(\/.*)?$/
            var result = regexp.test(link);
            return result;
        }
        return false;
    }
$scope.loadTags = function(query) { 
 return $http.get(  $scope.WSLink+"WS=FindTag&TagName="+query  ).then(function(res){
	   
	  return JSON.parse(res.data);
  }) ; 
};



$scope.SaveKnowldge=function () {
                                         
                                        $http({
                                            method: 'POST',
                                            url:  $scope.WSLink +  "WS=AddNewKnowledge",
                                            data: $scope.Knowledge,
                                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                                        }).
                                                success(function (response) {
                                                    toastr.success('Saved!', 'Opreation Complete');
$scope.Knowledge = {
        KnowledgeGUID: $scope.getGUID(),
        Title: "",
        Description: "",
        Steps: [{ Id: $scope.getGUID(), StepType: 1, StepContent: "" }],
        Tags: [{"text":'Bug',"TagID":0,   "IsSystemTag":false}],
		IsSolved:true
    };
    
                                                }).
                                                error(function (response) { 
                                                });
                                        return false;
                                    };
 


}]);