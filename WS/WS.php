 
<?php

include("DBHelper.php"); 


$ws = $_REQUEST["WS"];
$db = new DBHelper();
header('Content-type: application/json');
switch ($ws) {
	  case "GetKnowledgesBySearch":
	   $data = json_decode(file_get_contents("php://input"));
        $KnowledgesArr = $db->GetKnowledgesBySearch($data->Text);

        echo json_encode($KnowledgesArr, JSON_UNESCAPED_UNICODE);

        break;
		 
    case "GetKnowledges":
        $KnowledgesArr = $db->GetKnowledges($ACID=0);

        echo json_encode($KnowledgesArr, JSON_UNESCAPED_UNICODE);

        break;
		 case "GetKnowledge":
		 $KnowledgeID = $_REQUEST["KnowledgeID"];
        $KnowledgeArr = $db->GetKnowledge($KnowledgeID);

        echo json_encode($KnowledgeArr, JSON_UNESCAPED_UNICODE);

        break;
		 
		 case "FindTag":
		 $TagName = $_REQUEST["TagName"];
        $TagsArr = $db->FindTag($TagName);

        echo json_encode($TagsArr, JSON_UNESCAPED_UNICODE);

        break;
    case "AddNewKnowledge":
        $data = json_decode(file_get_contents("php://input"));

        $result = $db->AddNewKnowledge($data->KnowledgeGUID, $data->Title, $data->Description, $data->IsSolved, $data->Steps,$data->Tags);
    
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;


    case "UpdateKnowledge":
        $data = json_decode(file_get_contents("php://input"));

        $result = $db->UpdateKnowledge($data->KnowledgeGUID, $data->Title, $data->Description, $data->IsSolved, $data->Steps);
    
     echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
    

    case "DeleteKnowledge":
        $data = json_decode(file_get_contents("php://input"));

        $result = $db->DeleteKnowledge($data->id);



        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
		    case "AddNewTag":
        $data = json_decode(file_get_contents("php://input"));

        $result = $db->AddNewTag($data);



        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
		
		 
}
?>
