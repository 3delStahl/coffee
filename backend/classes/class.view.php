<?php
// Autor: Florian Giller
// Date: 13.11.2012 21:20 Uhr
// Update: Leon Bergmann - 15.11.2012 23:00 Uhr  
class view
{
	public $htmlHead 	= '<!DOCTYPE html>
	<html lang="de-DE">
	<head>
	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="style_backend.css" type="text/css">
	<script type="text/javascript" language="javascript" src="/coffee/backend/backend.js"></script>
	</head>
	<body>';
	
	public $htmlBottom 	= '</body></html>';
	
	
	
	public function viewLogin()
	{
		
		$output = '<div id="login">
		<h2>Login</h2>
		<form action="?" method="post">
		<ul>
		<li><label>Username:</label><input type="text" name="username" /></li>
		<li><label>Passwort:</label><input type="password" name="passwort" /></li>
		<input type="submit" class="formbutton" name="login" value="Anmelden" />
		</form>
		</ul>
		</div>';
		return $output;
	}
	
	public static function viewLeftMenu($menuPoint)
	{
		$output = '<div class="leftMenu">
				<ul>';
		switch($menuPoint)
		{
			case "school":
				$output .= '<li><a href="?v=teacherlist">Lehrerverwaltung</a></li>
					<li><a href="?v=subjectlist">Fächerverwaltung</a></li>
					<li><a href="?v=roomlist">Raumverwaltung</a></li>
					<li><a href="?v=listCombination">Lehrer-Fächer-Zuordnung</a></li>
					<li><a href="?v=proxy">Vertretungsstunden</a></li>';
			break;
			
			case "plan":
				$output .= '
							<li><a href="?v=roomPlan">Raumplan &Auml;nderungen</li>
							<li><a href="?v=newProxyInform">Lehrer vertreten</li>
							<li><a href="?v=lessonPlan">Vertretungsplan</a></li>
						  ';
		}
		return $output."</ul></div>";
	}
	
	public function viewMenu()
	{
		
		$output = '
		<div id="head">
		<div class="menu">
		<ul>
		<li><a href="?v=home">Startseite</a></li>
		<li><a href="?v=plan">Vertretungsplan</a></li>
		<li><a href="?v=userlist">Benutzerverwalten</a></li>
		<li><a href="?v=management">Schulverwaltung</a></li>
		<li><a href="?logout">Logout</a></li>
		</ul>
		</div>
		</div>';
		return $output;
		
	}

	public function messageBox($message,$messageType,$login = false)
	{
		if(empty($message)) return;
		
		switch($messageType)
		{
			case 0:
				$styleClass = "info";
				break;
			case 1:
				$styleClass = "success";
				break;
			case 2: 
				$styleClass = "warning";
				break;
			case 3:
				$styleClass = "error";
				break;
			default:
				$styleClass = "info";
				break;
		}
		
		if($login)
		{
			$styleClass .= " login";
		}
		
		$output = '<div class="'.$styleClass.'">'.$message.'</div>';
		
		return $output;
	}

	public function viewUserFormular($data)
	{
		if(!empty($data)) 	$action = "userEditSave&id=&id=".$data['id'];
		else				$action = "userAddSave";
		
		$output = '<div class="form"><form action="?v=userlist&a='.$data['id'].'" method="post">
		<ul>
		<li><label>Username:</label><input type="text" name="user[name]" value="'.$data['name'].'"/></li>
		<li><label>Email:</label><input type="text" name="user[email]" value="'.$data['email'].'"/></li>
		<li><label>Passwort:</label><input type="password" name="user[passwort1]" /></li>
		<li><label>Passwort wiederholen:</label><input type="password" name="user[passwort2]" /></li>
		<button type="submit" class="formbutton" name="userEdit">Speichern</button>
		</ul>
		</form>
		</div>';
		return $output;
	}
	public function viewUserList($userArray)
	{
				
		$table = "<table>\n";
		$table .= "<tr>\n";
		//NR Spalte erzeugen
		//$table .= "<th><h4>Nr.</h4></th>\n";
		//Tielspalten erzeugen
		$table .= "<th><h4>Name</h4></th>\n";		
		$table .= "<th><h4>E-Mail</h4></th>\n";	
		$table .= "<th><h4></h4></th>\n";	
		$table .= "</tr>\n";
		$table .= "</tr>\n";
		
		foreach($userArray as $userInfos)
		{
			$table .= "<tr>\n";
			$table .= "<td>".$userInfos['name']."</td>\n";
			$table .= "<td>".$userInfos['email']."</td>\n";
			$table .= "<td><a href = '?v=useredit&id=".$userInfos['id']."'>bearbeiten</a></td>\n";
			$table .= "<td><a onclick='if(!confirm(\"Eintrag von ".$userInfos['name']." entfernen?\")) return false;' href = '?v=userlist&a=userDelete&id=".$userInfos['id']."' >entfernen</a></td>\n";

			$table .= "</tr>\n";

		}
		
		$table .= "</table>\n";	


		return $table;
	}
	public function viewTeacherList($userArray)
	{
		if(empty($userArray)) return "Kein Lehrer eingetragen.";
		
		$table = "<table>\n";
		$table .= "<tr>\n";
		//NR Spalte erzeugen
		//$table .= "<th><h4>Nr.</h4></th>\n";
		//Tielspalten erzeugen
		$table .= "<th><h4>Anrede</h4></th>\n";
		$table .= "<th><h4>Vorname</h4></th>\n";	
		$table .= "<th><h4>Name</h4></th>\n";	
		$table .= "<th><h4>Kürzel</h4></th>\n";		
		$table .= "<th><h4>E-Mail</h4></th>\n";	
		$table .= "<th><h4></h4></th>\n";	
		$table .= "</tr>\n";
		
		foreach($userArray as $userInfos)
		{
			$table .= "<tr>\n";
			$table .= "<td>".$userInfos['anrede']."</td>\n";
			$table .= "<td>".$userInfos['vorname']."</td>\n";
			$table .= "<td>".$userInfos['name']."</td>\n";
			$table .= "<td>".$userInfos['kuerzel']."</td>\n";
			$table .= "<td> <a href='mailto:".$userInfos['email']."'>".$userInfos['email']."</a></td>\n";
			$table .= "<td><a href = '?v=teacheredit&id=".$userInfos['id']."'>bearbeiten</a></td>\n";
			$table .= "<td><a onclick='if(!confirm(\"Eintrag von ".$userInfos['name']." entfernen?\")) return false;' href = '?v=teacherlist&a=teacherDelete&id=".$userInfos['id']."' >entfernen</a></td>\n";

			$table .= "</tr>\n";

		}
		
		$table .= "</table>\n";	


		return $table;
	}
	
	
	public function viewTeacherFormular($data)
	{
			
		if(!empty($data)) 	$action = "teacherEditSave&id=".$data['id'];
		else				$action = "teacherAddSave";
		$output = '<form action="?v=teacherlist&a='.$action.'" method="post">
		<li><label>Anrede / Title:</label><input type="text" name="form[anrede]" value="'.$data['anrede'].'"/></li>
		<li><label>Vorname:</label><input type="text" name="form[vorname]" value="'.$data['vorname'].'"/></li>
		<li><label>Nachname:</label><input type="text" name="form[name]" value="'.$data['name'].'"/></li>		
		<li><label>Kürzel:</label><input type="text" name="form[kuerzel]" value="'.$data['kuerzel'].'"/></li>
		<li><label>E-Mail:</label><input type="text" name="form[email]" value="'.$data['email'].'"/></li>		
		<button type="submit" class="formbutton" name="userEdit">Speichern</button>
		</form>
		</ul>
		</div>';
		return $output;
		
	}
	
	public static function viewTeacherFaecher($dataArray)
	{
		if(!is_array($dataArray))
		{
			return false;
		}
		else
		{
			$tabel  = "<table>";
			$tabel .= "<tr>";
			$tabel .= "<th><h4>Fach</h4></th>";
			$tabel .= "<th><h4>Stunde</h4></th>";
			$tabel .= "</tr>";
			
			foreach($dataArray as $element)
			{
				$tabel .= "<tr>";
				$tabel .= "<td>".$element['fach']."</td>";
				$tabel .= "<td>".@$element['stunde']."</td>";
				$tabel .= "</tr>";
			}
			
			$tabel .= "</table>";
			return $tabel;
		}
	}
	
	public function viewSubjectList($userArray)
	{
		if(empty($userArray)) return "Kein Fach eingetragen.";
		
		$table = "<table>\n";
		$table .= "<tr>\n";
		$table .= "<th><h4>Name</h4></th>\n";	
		$table .= "<th><h4>Kürzel</h4></th>\n";		
		$table .= "<th><h4>Beschreibung</h4></th>\n";	
		$table .= "<th><h4></h4></th>\n";	
		$table .= "</tr>\n";
		
		foreach($userArray as $userInfos)
		{
			$table .= "<tr>\n";
			$table .= "<td>".$userInfos['name']."</td>\n";
			$table .= "<td>".$userInfos['kuerzel']."</td>\n";
			$table .= "<td>".$userInfos['beschreibung']."</td>\n";
			$table .= "<td><a href = '?v=subjectedit&id=".$userInfos['id']."'>bearbeiten</a></td>\n";
			$table .= "<td><a onclick='if(!confirm(\"Eintrag ".$userInfos['name']." entfernen?\")) return false;' href = '?v=subjectlist&a=subjectDelete&id=".$userInfos['id']."' >entfernen</a></td>\n";

			$table .= "</tr>\n";

		}
		
		$table .= "</table>\n";	


		return $table;
	}
	
	
	public function viewSubjectFormular($data)
	{
			
		if(!empty($data)) 	$action = "subjectEditSave&id=".$data['id'];
		else				$action = "subjectAddSave";
		$output = '<div class="form"><ul><form action="?v=subjectlist&a='.$action.'" method="post">
		<li><label>Name:</label><input type="text" name="form[name]" value="'.$data['name'].'"/></li>		
		<li><label>Kürzel:</label><input type="text" name="form[kuerzel]" value="'.$data['kuerzel'].'"/></li>
		<li><label>Beschreibung:</label><input type="text" name="form[beschreibung]" value="'.$data['beschreibung'].'"/></li>		
		<button type="submit" class="formbutton" name="userEdit">Speichern</button>
		</form></div>';
		return $output;
		
	}
	public function selctionList($array)
	{
		$selection = "";
		foreach($array as $part)
		{
			$selection .= "				<option value=".$part['id'].">".$part['name']."</option>\n";
			
		}
		return $selection;
		
	}
	public function viewLehrerFachZuordnung($teacherList,$subjectList)
	{
		$output = '
		<script type="text/javascript">
		var teacherList = eval(\'('.json_encode($teacherList).')\');
		var subjectList = eval(\'('.json_encode($subjectList).')\');
		</script>
		<form method="post" action="?v=listCombination&a=addSubjectTeacher">
		<p>Zuordnung nach:
			<select id="selectSwitch" size="1" onchange="ordnung()">
				<option selected >------------</option>
				<option value="1">Lehrer->F&auml;cher</option>
				<option value="2">F&auml;cher->Lehrer</option>
			</select>
			<input type="button" value="Reset" onclick="resetList()">
		</p>
		<div id="chooseContainer">
			<div id="selectionTop">
			</div>
			<div id="listContainer">
			</div>
		</div>
	    <hr>
	<input type="submit" value="Absenden">
    </form>';
	
return $output;
		
	}
	
	public function lfCombination($dataArray)
	{
		$output .= "<h2>Lehrer->Fächer</h2>\n";
		$output .= $this->multiTable($dataArray[0],'delete-subject-teacher');
		$output .= "<h2>Fächer->Lehrer</h2>\n";
		$output .= $this->multiTable($dataArray[1],'delete-subject-teacher');
		
		return $output;
	}
	
	private function multiTable($array,$action)
	{
		$table = "<table>\n";	
		foreach($array as $key=>$data)
		{
			$table .= "<tr><td>".$key."</td>\n";
			$table .= "<td><ul class='noneList'>\n";
			foreach($data as $lowLevelData)
			{
				$table .= "<li>".$lowLevelData[0]." <a class='tableListeElement' onclick='if(!confirm(\"Zuweisung `".$key." - ".$lowLevelData[0]."` entfernen?\")) return false;' href=?v=".$_GET['v']."&a=".$action."&id=".$lowLevelData[1].">x</a></li>\n";

			}
			
			$table .= "</ul></td></tr>\n";
			
		}
		$table .= "</table>\n";
		return $table;
	}
	
	public function viewNewProxy($teacherList)
	{
		$output = "<h2>Vertretbare Stunden</h2>";
		$output .= '<form action="?v='.$_GET['v'].'&a=saveTeacherProxy" method="post">
		<p>Lehrer:<select name="teacher" size="1">
				<option value="0" selected>---</option>'
				.$this->selctionList($teacherList).
				'</select></p>';
			
		$output .= '<p>Tag:
			<select name="day" size="1">
				<option value="0" selected>---</option>\n
				<option value="1">Montag</option>\n
				<option value="2">Dienstag</option>\n
				<option value="3">Mittwoch</option>\n
				<option value="4">Donnerstag</option>\n
				<option value="5">Freitag</option>\n
				<option value="6">Samstag</option>\n
				<option value="7">Sonntag</option>\n
			</select></p>
			<p>Stunde/n: <i>(Erlaubt: Stunden von 1 bis 12 oder Zeiträume 1-12) </i><input type="text" size="5" maxlength="5" name="stunde" >
			<input type="submit" value="Absenden">
			';				
		return $output;
			
	}
	
	public function viewRoom($roomList)
	{
		$output		= "<h2>Raumverwaltung</h2>";
		$output	   .= "<table><tr><th>Raumname</th><th>K&uuml;rzel</th></tr>";
		foreach($roomList as $room)
		{
    		$output .= "<tr>";
    		$output .= "<td>".$room['name']."</td>";
    		$output .= "<td>".$room['short']."</td>";
    		$output .= "<td><a href='?v=roomEdit&id=".$room['id']."'>bearbeiten</td>";
    		$output .= "<td><a onclick='if(!confirm(\"Eintrag ".$room['name']." entfernen?\")) return false;' href='?v=".$_GET['v']."&a=deleteRoom&id=".$room['id']."'>l&ouml;schen</a></td>";
    		$output .= "</tr>";
		}
		$output	   .= "</table>";
		$output    .= '<br /><form action="?v='.$_GET['v'].'&a=safeRoom" method="post"><label>Raumname: </label><input type="text" name="name"><span style="padding-left: 20px;"></span><label>K&uuml;rzel: </label><input type="text" name="short"><br><input class="formbutton" type="submit" value="Speichern"></form>';
		return $output;
	}

	public function editRoom($data)
	{
		$output ='<h2>Raum: '.$data['name'].' bearbeiten</h2><div class="form">
					<ul>
						<form action="?v=roomlist&a=safeRoom&new=0" method="post">
							<li><label>Raumname: </label><input type="text" name="name" value="'.$data['name'].'"/></li>
							<li><label>K&uuml;rzel: </label><input type="text" name="short" value="'.$data['short'].'" /></li>
							<button class="formbutton" type="submit">Speichern</button>
							<input type="hidden" name="id" value="'.$data['id'].'"/>
						</form>
					</ul>
				</div> ';
		return $output;
	}
	
	public function viewProxy($dataArray)
	{
		return $this->multiTable($dataArray,'delete-proxy-teacher');
	}
	
	public function viewRoomPlan($roomList,$settings)
	{
		
		$daysPre		= array(1 => 'Montag',2 => 'Dienstag',3 => 'Mittwoch',4 =>'Donnerstag',5 => 'Freitag',6 => 'Samstag',7 => 'Sonntag');
		
		$maxLessons	= $settings->getSession('max-lessons');
		$maxDay		= $settings->getSession('max-day');
		
		for($i = 1; $i <= $maxLessons; $i++)
		{
			$lessons .= '<option value="'.$i.'">'.$i.'</option>\n';
		}
		
		for($i = 0; $i <= $maxDay; $i++)
		{
			$days .= '<option value="'.$daysPre[$i].'">'.$daysPre[$i].'</option>';
		}
		
		foreach($roomList as $room)
		{
			$option .= '<option value="'.$room['id'].'">'.$room['name'].'</option>\n';
		}
		
		$div	 = '<div id="0" class="roomPlan"><select id="day[0]" name="day[0]">'.$days.'</select><select id="lesson[0]" name="lesson[0]">'.$lessons.'</select><select id="from[0]" name="from[0]"><option value="0">-------</option>'.$option.'<select><span> ------> </span><select id="to[0]" name="to[0]"><option value="0">-------</option>'.$option.'</select></div>';
		
		$output	 = '<h2>Tag | Stunde | Raum | neuer Raum</h2>';
		$output .= '<form id="roomPlan" method="post" action="?v='.$_GET['v'].'&a=safeRoomChanges">'.$div.'</form>';
		$output .= '<script type="text/javascript">var rooms = eval(\'('.json_encode($roomList).')\'); var maxLessons = eval(\'('.json_encode($maxLessons).')\');var maxDay = eval(\'('.json_encode($maxDay).')\');</script>';
		$output	.= '<button class="formbutton" onclick="newRoomChangeField();">weitere Raum&auml;nderung</button>';
		$output	.= '<button class="formbutton" onclick="safe();">Speichern</button>';
		return $output;
	}
	
	public function newProxySet($data)
	{
		//$data['teacher']
		//array("teacher"=>)
		
		foreach($data['teachers'] as $teacher)
		{
			$tAsID[$teacher['id']] = $teacher['vorname']." ".$teacher['name'];
		}
				foreach($data['subjects'] as $subjects)
		{
			$sAsID[$subjects['id']] = $subjects['name'];	
		}
			
		foreach($data['combination'] as $com)
		{
			
			$response['teacher'][$com['lehrer-id']] = array($tAsID[$com['lehrer-id']]);
			$response['subject'][$com['lehrer-id']][] = array($com['fach-id'],$sAsID[$com['fach-id']]);
				
		}		
		
		//print_r($response);
		
		$output = "DIE WAHL!";
		$output .= '<script type="text/javascript">var auswahl = '.json_encode($response).';</script><script type="text/javascript" src="http://aktuell.de.selfhtml.org/artikel/javascript/verkettete-auswahllisten/LinkedSelection.js"></script>';
		
		$output .= '<form onload="newProxyInformField();" id="vertretungAuswahl" method="post" action="?v='.$_GET['v'].'&a=newProxySet"></form>';
		$output	.= '<p><label id="teacherLabel" for="teacher">Lehrer:</label><select id="teacher"><option value="--">------</option></select><label id="subjectLabel" for="subject">Lesung:</label><select id="subject"><option value="--">------</option></select></p><div id="ergebnis"></div>';
		$output	.= '<button onclick="safe();">Speichern</button>';
		
		return $output;
	}
}


