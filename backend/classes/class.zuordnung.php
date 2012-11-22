<?php
// Autor: Florian Giller
// Date : 15.11.2012

class teacher_subject
{
	private $db;
	public $tableName = "faecher";
	
	public function __construct($database)
	{
		$this->db = $database;
		
	}
	
	public function saveCombination($switch, $formularArray)
	{
		if($switch == "1")
		{
			//Lehrer->F�cher
			print_r($formularArray);
			
		}
		elseif($switch == "2")
		{
			//F�cher->Lehrer
			
			
			
		}

		/*
if(empty($name))
			return false;
		if($this->fieldExist('name', $name) or $this->fieldExist('kuezel', $kuerzel))
			return false;

		$sql = "INSERT INTO ".$this->tableName." (name, kuerzel, beschreibung) VALUES ('$name', '$kuerzel', '$beschreibung')";
		if($this->db->querySend($sql))
		{
			return true;
		}	
		
*/
		
	}
	
	
	public function editSubject($formularArray)
	{
		$id 			= $formularArray['id'];
		$name 			= $formularArray['name'];
		$beschreibung	= $formularArray['beschreibung'];
		$kuerzel 		= $formularArray['kuerzel'];		

		if(!is_numeric($id) or empty($name))
			return false;
			
		$sql = "UPDATE ".$this->tableName." SET name='".$name."', kuerzel='".$kuerzel."', beschreibung='".$beschreibung."' WHERE id = $id";
		if($this->db->querySend($sql))
		{
			
			return true;
		}
		
	}

	public function getSubjectDetails($id)
	{
		if(is_numeric($id))
		{
			if($userDetails = $this->db->queryAsSingelRowAssoc("SELECT * FROM ".$this->tableName." WHERE id = $id"))
			{
				return $userDetails;
				
				
			}
			
			else return false;
		}
		
		else return false;	
	}

	public function listAllSubject()
	{
		$userList = $this->db->queryAsAssoc("SELECT id, name, kuerzel, beschreibung FROM ".$this->tableName);
		
		return $userList;	
	}
	
	private function fieldExist($field,$content)
	{
		if($this->db->querySend("SELECT * FROM ".$this->tableName." WHERE $field = $content"))
			return true;
		else 
			return false;
		
	}
	
	public function deleteSubject($id)
	{
		if(!is_numeric($id)) return false; 
		
		if($this->db->querySend("DELETE FROM `".$this->tableName."` WHERE `id` = $id"))
			return true;
		else
			return false;
		
		
	}

}

?>