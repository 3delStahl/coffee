<?php
// Autor: Florian Giller
// Date : 05.11.2012
// Update: Leon Bergmann - 21.11.2012 20:00 Uhr  
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/coffee/static/class.database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/coffee/static/class.loginAPI.php");

require_once("classes/class.user.php");
require_once("classes/class.view.php");
require_once("classes/class.teacher.php");
require_once("classes/class.faecher.php");
require_once("classes/class.zuordnung.php");

$view 		= new view();
$database	= new database();
$user 		= new userAdministration($database);
$teacher	= new teacher($database);
$subject	= new subject($database);
$teacher_subject = new teacher_subject($database);

$database->databaseName = "backend";

$contentField = "<div id='content'>";

if(isset($_GET['dev1']))
{
	$_SESSION['auth'] = true;
}
//Wenn User nicht eingeloggt

	//Wenn Loginbutton gedr&uuml;ckt
	if(isset($_POST['login']))
	{
		//Login Check
		try
		{
			$login		= new loginAPI("backend",$database);
			$login->makeLogin($_POST['username'],$_POST['passwort']);
		}
		catch(Exception $e)
		{
			$message		= $e->getMessage();
			$messageType	= $e->getCode();
		}
	}
	if(isset($_GET['logout']))
	{		
		session_unset();
		$message 		= "Sie wurden ausgeloggt";
		$messageType	= 1;

	}

//Wenn User eingeloggt
//
if($_SESSION['auth'] and !isset($_GET['dev']))
{
	$menu = "";
	$menu = $view->viewMenu();
	//ACTIONS
	try
	{
		$messageType		= 1;
		switch($_GET['a'])
		{
			case "userAddSave":
					$userInfos 		= $_POST['user'];
					$message		= $user->addUser($userInfos);
				break;
				
			case "userEditSave":
					$userInfos 			= $_POST['user'];
					$userInfos['id']	= $_GET['id'];				
					$message			= $user->editUser($userInfos);
					
				break;
			
			case "userDelete":
					$message 		= $user->deleteUser($_GET['id']);
				break;
			
			case "teacherAddSave":
					$data = $_POST['form'];
					$message 		= $teacher->addTeacher($data);
				break;
				
			case "teacherEditSave":
					$data 		= $_POST['form'];
					$data['id'] = $_GET['id'];
					$message	= $teacher->editTeacher($data);	 
				break;
			
			case "teacherDelete":
					$message 		= $teacher->deleteTeacher($_GET['id']);
				break;
				
			case "subjectAddSave":
				$data = $_POST['form'];
				if($subject->addSubject($data))
					$message 		= "Neues Fach angelegt";
	
				break;
			case "subjectEditSave":
				$data 		= $_POST['form'];
				$data['id'] = $_GET['id'];
				if($subject->editSubject($data))
						$message 		= "Fach bearbeitet";
				break;
			
			case "subjectDelete":
				if($subject->deleteSubject($_GET['id']))
					$message 		= "Fach gel&oul;scht";
				break;
			
			case "addSubjectTeacher":
							
				$message = $teacher_subject->saveCombination($_POST);
				break;
			
				
		}
	}
	catch(Exception $e)
	{
				
		$message 		= $e->getMessage();
		$messageType	= $e->getCode(); 
	}
	
	//VIEWS		
	try
	{	
		switch($_GET['v'])
		{

			case "userlist":
					$contentField 	.= "<h2>Benutzerverwaltung</h2><ul>";
					$contentField 	.= "<a href='?v=useradd'>Benutzer hinzuf&uuml;gen</a>";
					$contentField 	.= "<h3>Benutzerliste</h3><ul>";
					$contentField 	.= $view->viewUserList($user->listAllUsers());	
				break;
	
			case "useradd":
					$contentField 	.= "<h2>Benutzer anlegen</h2><ul>";
					$contentField 	.= $view->viewUserFormular(null);	
				break;
			
			case "management":
					$contentField	.= "<h2>Verwaltung</h2>";
					$leftMenu		.= view::viewLeftMenu();
				break;
			
			case "useredit":
				$contentField 	.= "<h2>Benutzer bearbeiten</h2><ul>";
				$contentField 	.= $view->viewUserFormular($user->getUserDetails($_GET['id']));
				//$contentField 	.= $view->viewUserAddFormular();	
				break;
	
			case "teacherlist":
				$contentField 	.= "<h2>Lehrerverwaltung</h2><ul>";
				//$contentField 	.= "<h2>Benutzerhinzuf&uuml;gen</h2><ul>";
				$contentField 	.= "<a href='?v=teacheradd'>Lehrer hinzuf&uuml;gen</a>";
				$contentField 	.= "<h3>Lehrerliste</h3><ul>";
				$leftMenu		.= view::viewLeftMenu();
				$contentField 	.= $view->viewTeacherList($teacher->listAllTeacher());			
				break;
	
			case "teacheradd":
				$contentField 	.= "<h2>Lehrer hinzuf&uuml;gen</h2><div class='form'><ul>";
				$leftMenu		.= view::viewLeftMenu();
				$contentField 	.= $view->viewTeacherFormular(array());	
				break;
	
			case "teacheredit":
				$contentField 	.= "<h2>Lehrer bearbeiten</h2><div class='form'><ul>";
				$contentField 	.= $view->viewTeacherFormular($teacher->getTeacherDetails($_GET['id']));
				$contentField   .= view::viewTeacherFaecher($teacher->getAllFeacherForTeacher($_GET['id']));
				$leftMenu		.= view::viewLeftMenu();
				//$contentField 	.= $view->viewUserAddFormular();
				break;
				
			case "subjectlist":
				$contentField 	.= "<h2>F&auml;cherverwaltung</h2><ul>";
				$contentField 	.= "<a href='?v=subjectadd'>Fach hinzuf&uuml;gen</a>";
				$contentField 	.= "<h3>F&auml;cherliste</h3><ul>";
				$contentField 	.= $view->viewSubjectList($subject->listAllSubject());
				$leftMenu		.= view::viewLeftMenu();			
				break;
	
			case "subjectadd":
				$contentField 	.= "<h2>Fach hinzuf&uuml;gen</h2><ul>";
				$contentField 	.= $view->viewSubjectFormular(array());
				$leftMenu		.= view::viewLeftMenu();
				break;
	
			case "subjectedit":
				$contentField 	.= "<h2>F&auml;cher bearbeiten</h2><ul>";
				$contentField 	.= $view->viewSubjectFormular($subject->getSubjectDetails($_GET['id']));
				$leftMenu		.= view::viewLeftMenu();
				//$contentField 	.= $view->viewUserAddFormular();
				break;
			case "teacher-subject":
				$contentField .= "<h2>Lehrer F&auml;cher zuordnen</h2>";
				$contentField .= $view->viewLehrerFachZuordnung($teacher->listAllTeacher(),$subject->listAllSubject());
				$leftMenu		.= view::viewLeftMenu();
				break;
				
			case "listCombination":
			
				$contentField .= $view->lfCombination($teacher_subject->listComnination());
				$leftMenu		.= view::viewLeftMenu();
	
					break;
	
				
			case "home":
			default:
				$contentField .= "<h2>Willkommen auf der Startseite</h2>";
				break;
		}
	}
	catch(Exception $e)
	{
				
		$message 		= $e->getMessage();
		$messageType	= $e->getCode(); 
	}

	$message = $view->messageBox($message,$messageType);

}
else
{	
	//Loginpage
	$message = $view->messageBox($message,$messageType,1);
	$contentField  = '<div id="contentLogin">';
	$contentField .= $view->ViewLogin();

}

$contentField .= "</div>";

$output = $view->htmlHead;
$output .= $menu;
$output .= $message;
$output .= $leftMenu;
$output .= $contentField;
$output .= $view->htmlBottom;

echo $output;
?>