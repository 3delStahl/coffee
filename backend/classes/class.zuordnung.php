<?php
// Autor: Florian Giller
// Date : 15.11.2012
// Update: Leon Bergmann - 10.12.2012 08:39 Uhr 
class teacher_subject
{
	private $db;
	public $tableName = "lehrer-faecher";
	
	
	public function __construct($database)
	{
		$this->db = $database;
	}
	
	private function checkFormVars(&$array,$firstKey)
	{
		
		if(!$array[$firstKey][0]) 
			throw new Exception("Es wurden nicht alle Felder ausgef&uuml;llt.",3);
		
		//$array = array_unique($array);
		foreach($array[$firstKey] as $key=>$row)
		{
			if(!$row) unset($array[$firstKey][$key]);
			
		}
		
	}
	
	public function saveCombination($form)
	{
		$this->checkFormVars($form,'teacher');
		$this->checkFormVars($form,'subject');
		
		if(!is_numeric($form['switch']))
			return false;
			
			
		switch($form['switch'])
		{
			//Lehrer->Fächer
			case "1":
				$l_id = $form['teacher'][0];
				$sql = "";
				foreach($form['subject'] as $f_id)
				{
					$sqlData[$l_id.'-'.$f_id] = "($l_id,$f_id)";
					
				}
				break;
				
			//Fächer->Lehrer
			case "2":
				$f_id = $form['subject'][0];
				$sql = "";
				foreach($form['teacher'] as $l_id)
				{
					$sqlData[$l_id.'-'.$f_id] = "($l_id,$f_id)";
					
				}
				break;
				
			default:
				throw new Exception("Fehler beim Zuordnen.",3);
				break;
	
		}
		
				/*
if($this->fieldExist('name', $name) or $this->fieldExist('kuezel', $kuerzel))
			return false;
*/
		$sql = "Insert IGNORE INTO `".$this->tableName."` (`lehrer-id`,`fach-id`) VALUES ".implode(", ", $sqlData);
		
		
		if($this->db->querySend($sql))
			return "Zuordnung wurde gespeichert.";
		else 
			return "Zuordnung fehlgechlagen.";
	}
	
	public function listComnination()
	{
		$list = $this->db->queryAsAssoc("SELECT `lehrer`.`name` as 'lehrerName',`faecher`.`name` as 'faecherName', `lehrer-faecher`.`id` FROM `lehrer-faecher` 
		INNER JOIN `faecher` ON `faecher`.`id` = `lehrer-faecher`.`fach-id`
		INNER JOIN `lehrer` ON `lehrer`.`id`=`lehrer-faecher`.`lehrer-id`");
		
		foreach($list as $row)
		{
			$ordList[0][$row['lehrerName']][] = array($row['faecherName'],$row['id']);
			$ordList[1][$row['faecherName']][] = array($row['lehrerName'],$row['id']);
			
		}	
		ksort($ordList[0]);
		ksort($ordList[1]);
		
		return $ordList;

		
	}	
	public function getComninationID()
	{
		return $list = $this->db->queryAsAssoc("SELECT * FROM `".$this->tableName."`");
		
		
	}

	public function getSubjectDetails($id)
	{
	
		if(is_numeric($id))
		{
			if($userDetails = $this->db->queryAsSingelRowAssoc("SELECT * FROM ".$this->tableName." WHERE id = $id"))
			{
				return $userDetails;
			}
			else 
			{
				return false;
			}
		}
		else
		{
			return false;	
		}
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
	
	public function deleteZuordnung($id)
	{
		if(!is_numeric($id)) return false; 
		
		if($this->db->querySend("DELETE FROM `".$this->tableName."` WHERE `id` = $id"))
			return "Zuordnung entfernt";
		else
			return false;
		
		
	}

}

?>