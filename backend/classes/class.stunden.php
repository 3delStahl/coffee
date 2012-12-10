<?php
// Autor: Florian Giller
// Date : 15.11.2012

class teacher_lession
{
	private $db;
	public $maxStunden = 12;
	public $tableName = "lehrer-freie-stunden";
	
	
	public function __construct($database)
	{
		$this->db = $database;
	}
	
	public function checkTimecode($timecode)
	{
		$timecodeSplit = explode('x', $timecode);
		if(empty($timecode) or $timecodeSplit[0] < 1 or $timecodeSplit[0] > 7 or $timecodeSplit[1] < 1 or  $timecodeSplit[1] > 12)
		throw new Exception("Eingabe fehlerhaft! (Timecode)",3);
		
	}
	
	public function timecodeAsText($timecode)
	{
		$wochentage = array(1=>"Montag",2=>"Dienstag",3=>"Mittwoch",4=>"Donnerstag",5=>"Freitag",6=>"Samstag",7=>"Sonntag");
				
		
		$this->checkTimecode($timecode);
			
		$timecodeSplit = explode('x', $timecode);	
		$tag 	= $wochentage[$timecodeSplit[0]];
		$stunde = $timecodeSplit[1];
		
		return "Am ".$tag." in der ".$stunde.". Stunde";
			
	}
	
	private function checkFormDate($stunde)
	{
		$stunde = preg_replace("/[^0-9-]/" ,"" , $stunde);
		if(is_numeric($stunde) && strlen($stunde) <= 2 && $stunde >= 1 && $stunde <= 12)
		{
			return array($stunde);
		}
		else
		{
			//M�glicherweise mit Komma getrennte mehrfach auswahl		
			$stunde = explode("-", $stunde);
			if($stunde[0] >= 1 && $stunde[0] < $this->maxStunden && $stunde[1] >= 1 && $stunde[1] <= $this->maxStunden && $stunde[0] <= $stunde[1])
			{
				
				for($i=$stunde[0];$i<=$stunde[1];$i++)
				{
					$std_array[] = $i;
				}
								
			}
				return $std_array; 
				//implode(",", $std_array);	
		}	
	}
	
	public function saveCombination($form)
	{
		//$this->checkTimecode();

		//return $this->checkFormDate($form['stunde']);
		foreach($this->checkFormDate($form['stunde']) as $einzelneStunde)
		{
			$timecode = $form['day']."x".$einzelneStunde;
			$sqlData[] = "('".$form['teacher']."','".$timecode."')";
			
		}
		return $sql = "Insert INTO `".$this->tableName."` (`lehrer-id`,`timecode`) VALUES ".implode(", ", $sqlData);
		
	//throw new Exception("Datenbankfehler.", 3);
				
	}
	
	private function lfZuordnungQuerySend($sqlData)
	{
		$sql = "Insert INTO `".$this->tableName."` (`lehrer-id`,`fach-id`) VALUES ".implode(", ", $sqlData);
		return $this->db->querySend($sql);
	}
	
	public function listComnination()
	{
		$list = $this->db->queryAsAssoc("SELECT `lehrer`.`name` as 'lehrerName',`faecher`.`name` as 'faecherName� FROM `lehrer-faecher` 
		INNER JOIN `faecher` ON `faecher`.`id` = `lehrer-faecher`.`fach-id`
		INNER JOIN `lehrer` ON `lehrer`.`id`=`lehrer-faecher`.`lehrer-id`");
		
		foreach($list as $row)
		{
			$ordList[0][$row['lehrerName']][] = $row['faecherName'];
			$ordList[1][$row['faecherName']][] = $row['lehrerName'];
			
		}	

		return $ordList;

		
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