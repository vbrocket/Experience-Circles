 
<?php

class DBHelper
{
    
    public $servername = "158.85.191.244";
    public $username = "root";
    public $password = "r00t";
    public $dbname = "coda";
    
    public function GetKnowledges($ACID)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sqlMyKnowledges = "select ID, Title,   Description ,  IsSolved , clientGUID  from knowledge";
        $result          = $conn->query($sqlMyKnowledges);
        $ArrayKnowledges = array();
        
        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_array(MYSQL_ASSOC)) { 
				   $ArrayKnowledges[]  = array( 'Id' => $row["ID"] ,
				   'Title' =>  $row["Title"] ,
				   'Description' =>  $row["Description"] ,
                'Steps' => $this->GetKnowledgeSteps($row["ID"]),
                'Tags' => $this->GetKnowledgeTags($row["ID"])
            );
            }
            return json_encode($ArrayKnowledges);
        } else {
            return "[]";
        }
        $conn->close();
    }
    
	  public function GetKnowledge($KnowledgeID)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sqlMyKnowledges = "select ID, Title,   Description ,  IsSolved , clientGUID  from knowledge where ID=".$KnowledgeID;
        $result          = $conn->query($sqlMyKnowledges);
        $ArrayKnowledges = array();
        
        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_array(MYSQL_ASSOC)) { 
				   $ArrayKnowledges[]  = array( 'Id' => $row["ID"] ,
				   'Title' =>  $row["Title"] ,
				   'Description' =>  $row["Description"] ,
                'Steps' => $this->GetKnowledgeSteps($row["ID"]),
                'Tags' => $this->GetKnowledgeTags($row["ID"])
            );
            }
            return json_encode($ArrayKnowledges);
        } else {
            return "[]";
        }
        $conn->close();
    }
	public function GetKnowledgesBySearch($Text)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
       $sqlTags = " select ID, Title,   Description , 
 IsSolved , clientGUID  from knowledge where Title like '%".$Text  ."%'  or Description like  '%".$Text ."%'".
  " or ID in   (
        select  knowledgeID
        from   knowledgeSteps
        where    content like '%".$Text ."%' )  or ID in  
    (
        select  knowledgeTags.knowledgeID
        from   Tags inner join knowledgeTags on Tags.TagID=knowledgeTags.TagID
        where    Tags.TagName like '%".$Text  ."%'". ")";
        
        $result          = $conn->query($sqlTags);
        $ArrayTags = array();
        
        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $ArrayTags[] = $row;
            }
            return json_encode($ArrayTags);
        } else {
            return "[]";
        }
        $conn->close();
    }
	public function FindTag($TagName)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sqlTags = "select  TagName as text, TagName as text,TagID,   IsSystemTag  from Tags where TagName like '%" . $TagName ."%'";
	  
        $result          = $conn->query($sqlTags);
        $ArrayTags = array();
        
        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $ArrayTags[] = $row;
            }
            return json_encode($ArrayTags);
        } else {
            return "[]";
        }
        $conn->close();
    }
    
    
    public function AddNewKnowledge($KnowledgeGUID, $Title, $Description, $IsSolved, $Steps,$Tags)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT ID FROM knowledge WHERE clientGUID='" . $KnowledgeGUID . "'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            
            
            return $this->UpdateKnowledge($KnowledgeGUID, $Title, $Description, $IsSolved, $Steps,$Tags);
            
            
        } else {
            $sql = "INSERT INTO knowledge ( Title,   Description ,  IsSolved , clientGUID)
VALUES ('" . $Title . "','" . $Description . "'," . $IsSolved . ",'" . $KnowledgeGUID . "')";
            
            if ($conn->query($sql) === TRUE) {
                $knowledgeID    = 0;
                $sqlknowledgeID = "select ID   from knowledge WHERE clientGUID='" . $KnowledgeGUID . "'";
                ;
                $resultsqlknowledgeID = $conn->query($sqlknowledgeID);
                
                if ($resultsqlknowledgeID->num_rows > 0) {
                    
                    while ($row = $resultsqlknowledgeID->fetch_array(MYSQL_ASSOC)) {
                        $knowledgeID = $row['ID'];
                    }
                    
                }
                if ($knowledgeID === 0) {
                    return 0;
                }
                $this->AddNewSteps($knowledgeID, $Steps);
				$this->AssignTag($knowledgeID, $Tags);
                return 1;
            } else {
                return 0;
            }
        }
        
        
        $conn->close();
    }
    
    public function AddNewSteps($knowledgeID, $Steps)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        foreach (  $Steps as $step) {
            $sql = "SELECT ID FROM knowledgeSteps WHERE clientGUID='" . $step->Id . "'";
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                
                
                return $this->UpdateStep($step);
            } else {
                
                
                $sql = "INSERT INTO knowledgeSteps ( knowledgeID,   StepTypeID ,  Content , clientGUID)
VALUES ( " . $knowledgeID . " , " . $step->StepType . " ,'" . $step->StepContent . "','" . $step->Id . "')";
                
                if ($conn->query($sql) === TRUE) {
                  
                } else {
                     
                }
            }
        }
         $conn->close();
    }
	
	
	  public function GetKnowledgeSteps($knowledgeID)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
		$ArraySteps =  array();
     
            $sql = "SELECT knowledgeID,   StepTypeID ,  Content , clientGUID FROM knowledgeSteps WHERE knowledgeID= " . $knowledgeID;
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                
                  while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $ArraySteps[] = $row;
            }
			}else{
				return ;
			} 
		
         $conn->close();
		 return  $ArraySteps;
    }
	
	  public function GetKnowledgeTags($knowledgeID)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
		$ArrayTags =  array();
       
            $sql = "SELECT TagName,   Tags.TagID  FROM knowledgeTags inner join Tags on 
			knowledgeTags.TagID=Tags.TagID WHERE knowledgeTags.knowledgeID= " . $knowledgeID;
          
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                
                  while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                $ArrayTags[] = $row;
            }
            }else{
				return ;
			} 
       
		
         $conn->close();
		 return  $ArrayTags;
    }
    public function DeleteKnowledge($id)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "DELETE FROM  knowledge  WHERE  id=" . $id;
        if ($conn->query($sql) === TRUE) {
            return 1;
        } else {
            return 0;
        }
        $conn->close();
    }
    
    public function UpdateKnowledge($KnowledgeGUID, $Title, $Description, $IsSolved, $Steps,$Tags)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $knowledgeID    = 0;
        $sqlknowledgeID = "select ID   from knowledge WHERE clientGUID='" . $KnowledgeGUID . "'";
        ;
        $resultsqlknowledgeID = $conn->query($sqlknowledgeID);
        
        if ($resultsqlknowledgeID->num_rows > 0) {
            
            while ($row = $resultsqlknowledgeID->fetch_array(MYSQL_ASSOC)) {
				 
                $knowledgeID = $row['ID'];
            }
            
        }
        if ($knowledgeID === 0) {
            return 0;
        }
        $sql = "UPDATE knowledge SET Title='" . $Title . "'," . "Description='" . $Description . "'," . "IsSolved=" . $IsSolved . " WHERE clientGUID='" . $KnowledgeGUID . "'";
        $this->AddNewSteps($knowledgeID, $Steps);
        $this->AssignTag($knowledgeID, $Tags);
        if ($conn->query($sql) === TRUE) {
            return 1;
        } else {
            return 0;
        }
        $conn->close();
    }
    
    public function UpdateStep($Steps)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        
        $sql = "UPDATE knowledgeSteps SET StepTypeID=" . $Steps->StepType  . "," . "Content='" . $Steps->StepContent . " WHERE clientGUID='" . $Steps->Id . "'";
        
        if ($conn->query($sql) === TRUE) {
            return 1;
        } else {
            return 0;
        }
        $conn->close();
    }
	
	
	
	
    public function AddNewTag($Tag)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
       
            $sql = "SELECT TagID FROM Tags WHERE TagName='" . $Tag->text . "'";
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                
                
               while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                       return $row['TagID'];
                    }
            } else {
                
                
                $sql = "INSERT INTO Tags ( TagName,   IsSystemTag  )
VALUES ( '" . $Tag->text . "' ,  0   )";
                
                if ($conn->query($sql) === TRUE) {
					 $sql = "SELECT TagID FROM Tags WHERE TagName='" . $Tag->text . "'";
					  $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                
                
               while ($row = $result->fetch_array(MYSQL_ASSOC)) {
                       return $row['TagID'];
                    }
            } 
                } else {
                    return 0;
                }
            }
       
         $conn->close();
    }
	
	 public function AssignTag($knowledgeID, $Tags)
    {
		$this->UnassignTags($knowledgeID);
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        foreach (  $Tags as $Tag) {
			if(isset($Tag->TagID) &&is_object($Tag->TagID)){
				  $sql = "SELECT ID FROM knowledgeTags WHERE knowledgeID= " . $knowledgeID . " and TagID= ".$Tag->TagID;
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                
                
                
            } else {
                
                
                $sql = "INSERT INTO knowledgeTags ( knowledgeID,   TagID )
VALUES ( " . $knowledgeID . " , " . $Tag->TagID    . " )";
                
                if ($conn->query($sql) === TRUE) {
                  
                } else {
                   
                }
            }
			}
			
			else{
				$TagID= $this->AddNewTag($Tag);
                $sql = "INSERT INTO knowledgeTags ( knowledgeID,   TagID )
VALUES ( " . $knowledgeID . " , " .$TagID    . " )";
                
                if ($conn->query($sql) === TRUE) {
                  
                } else {
                   
                }
				  
			}
          
        }
         $conn->close();
    }
	
	 public function UnassignTags($knowledgeID)
    {
        // Create connection
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "DELETE FROM  knowledgeTags  WHERE  knowledgeID=" . $knowledgeID;
        if ($conn->query($sql) === TRUE) {
            return 1;
        } else {
            return 0;
        }
        $conn->close();
    }
    
}

?>
 
 