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

	public function viewMenu()
	{
		
		$output = '
		<div id="head">
		<div class="menu">
		<ul>
		<li><a href="?v=home">Startseite</a></li>
		<li><a href="?v=plan">Vertretungsplan</a</li>
		<li><a href="?v=userlist">Benutzerverwalten</a></li>
		<li><a href="?v=teacherlist">Lehrerverwaltung</a></li>
		<li><a href="?v=subjectlist">Fächerverwaltung</a></li>
		<li><a href="?v=teacher-subject">Lehrer-Fächer-Zuordnung</a></li>
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
		$table .= "<th><h4>Vorname</h4></th>\n";	
		$table .= "<th><h4>Name</h4></th>\n";	
		$table .= "<th><h4>Kürzel</h4></th>\n";		
		$table .= "<th><h4>E-Mail</h4></th>\n";	
		$table .= "<th><h4></h4></th>\n";	
		$table .= "</tr>\n";
		
		foreach($userArray as $userInfos)
		{
			$table .= "<tr>\n";
			$table .= "<td>".$userInfos['vorname']."</td>\n";
			$table .= "<td>".$userInfos['name']."</td>\n";
			$table .= "<td>".$userInfos['kuerzel']."</td>\n";
			$table .= "<td>".$userInfos['email']."</td>\n";
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
			$selection .= "<option id=".$part['id'].">".$part['name']."</option>";
			
		}
		return $selection;
		
	}
	public function viewLehrerFachZuordnung($teacherList,$subjectList)
	{
		$output = '
		<form method="post">
		<p>Zuordnung nach:
			<select name="zuordnung" id="auswahl" size="1" onchange="ordnung(this.form.zuordnung.options[this.form.zuordnung.selectedIndex].value)">
			<option selected >------------</option>
			<option value="1">Lehrer->F&auml;cher</option>
			<option value="2">F&auml;cher->Lehrer</option>
			</select>
			<input type="button" value="Reset" onclick="resetList()">
		</p>
		<div id="chooseContainer">
		
		</div>
	    <hr>
	<input type="submit" value="Absenden">
    </form>
    
	<div id="teacherSource" style="display:none">
		<select name="teacherlist[]" size="1">
			<option id="0" selected>---</option>
			'.$this->selctionList($teacherList).'
		</select>
	</div>
	<div id="subjectSource" style="display:none">
		<select name="subjetlist[]" size="1">
			<option id="0" selected>---</option>
			'.$this->selctionList($subjectList).'
		</select>
	</div>
';
return $output;
		
	}
}

