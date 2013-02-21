<?php
// Autor: Florian Giller
// Date : 05.11.2012
// Update: Leon Bergmann - 23.01.2013 08:53 Uhr   
//error_reporting(1);
require_once($_SERVER["DOCUMENT_ROOT"]."/coffee/static/class.settings.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/coffee/static/class.database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/coffee/static/class.loginAPI.php");
require_once("classes/class.user.php");
require_once("classes/class.view.php");
require_once("classes/class.teacher.php");
require_once("classes/class.faecher.php");
require_once("classes/class.zuordnung.php");
require_once("classes/class.stunden.php");
require_once("classes/class.room.php");


$view 				= new view();
$database			= database::singelton("coffee");
$settings			= settings::singelton($database);
$user 				= new userAdministration($database);
$teacher			= new teacher($database);
$subject			= new subject($database);
$teacher_subject 	= new teacher_subject($database);
$teacher_lession 	= new teacher_lession($database);
$room				= new room($database);



$contentField = "<div id='content'>";

if(isset($_GET['dev1']))
{
	$settings->setSession('auth',true);
}
//Wenn User nicht eingeloggt

	//Wenn Loginbutton gedr&uuml;ckt
	if(isset($_POST['login']))
	{
		//Login Check
		try
		{
			$login		= new loginAPI("backend",$database);
			$login		->makeLogin($_POST['username'],$_POST['passwort'],$settings);
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
if($settings->getSession('auth') and !isset($_GET['dev']))
{
	$menu = "";
	$menu = $view->viewMenu();
	$settings->setSettingsToSessions();
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
				
			case "delete-subject-teacher":
							
				$message = $teacher_subject->deleteZuordnung($_GET['id']);
				break;
				
			case "saveTeacherProxy":		
				$message = $teacher_lession->saveCombination($_POST);
				break;
			
			case "delete-proxy-teacher":				
				$message = $teacher_lession->deleteEntry($_GET['id']);
				break;
				
			case "roomEdit":
				$message = $room->editRoom($_POST);
				break;
			
			case "safeRoom":
				$message = $room->safeRoom($_POST,$_GET['new']);
				break;
			
			case "deleteRoom":
                $message = $room->deleteRoom($_GET['id']);
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
					$leftMenu		.= view::viewLeftMenu("school");
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
				$leftMenu		.= view::viewLeftMenu("school");
				$contentField 	.= $view->viewTeacherList($teacher->listAllTeacherDetails());			
				break;
	
			case "teacheradd":
				$contentField 	.= "<h2>Lehrer hinzuf&uuml;gen</h2><div class='form'><ul>";
				$leftMenu		.= view::viewLeftMenu("school");
				$contentField 	.= $view->viewTeacherFormular(array());	
				break;
	
			case "teacheredit":
				$contentField 	.= "<h2>Lehrer bearbeiten</h2><div class='form'><ul>";
				$contentField 	.= $view->viewTeacherFormular($teacher->getTeacherDetails($_GET['id']));
				$contentField   .= view::viewTeacherFaecher($teacher->getAllFeacherForTeacher($_GET['id']));
				$leftMenu		.= view::viewLeftMenu("school");
				//$contentField 	.= $view->viewUserAddFormular();
				break;
				
			case "subjectlist":
				$contentField 	.= "<h2>F&auml;cherverwaltung</h2><ul>";
				$contentField 	.= "<a href='?v=subjectadd'>Fach hinzuf&uuml;gen</a>";
				$contentField 	.= "<h3>F&auml;cherliste</h3><ul>";
				$contentField 	.= $view->viewSubjectList($subject->listAllSubject());
				$leftMenu		.= view::viewLeftMenu("school");			
				break;
	
			case "subjectadd":
				$contentField 	.= "<h2>Fach hinzuf&uuml;gen</h2><ul>";
				$contentField 	.= $view->viewSubjectFormular(array());
				$leftMenu		.= view::viewLeftMenu("school");
				break;
	
			case "subjectedit":
				$contentField 	.= "<h2>F&auml;cher bearbeiten</h2><ul>";
				$contentField 	.= $view->viewSubjectFormular($subject->getSubjectDetails($_GET['id']));
				$leftMenu		.= view::viewLeftMenu("school");
				//$contentField 	.= $view->viewUserAddFormular();
				break;
			case "teacher-subject":
				$contentField .= "<h2>Lehrer F&auml;cher zuordnen</h2>";
				$contentField .= $view->viewLehrerFachZuordnung($teacher->listAllTeacher(),$subject->listAllSubject());
				$leftMenu	  .= view::viewLeftMenu("school");
				break;
				
			case "listCombination":
				$contentField .= "<h2>Lehrer-F&auml;cher-Zuordnung</h2><ul><a href='?v=teacher-subject'>Zuordnung hinzuf&uuml;gen</a><h3>Zuordnung</h3><ul>";
				$contentField .= $view->lfCombination($teacher_subject->listComnination());
				$leftMenu		.= view::viewLeftMenu("school");
				break;
				
			
			case "proxyAdd":
				$contentField 	.= $view->viewNewProxy($teacher->listAllTeacher());
				$leftMenu		.= view::viewLeftMenu("school");
				break;	
				
			case "proxy":
				$contentField 	.= "<a href='?v=proxyAdd'>Vertretung add</a>";
				$contentField   .= $view->viewProxy($teacher_lession->listComnination());
				$leftMenu		.= view::viewLeftMenu("school");
				break;
			
			case "roomlist":
				$contentField	.= $view->viewRoom($room->getRoomList());
				$leftMenu		.= view::viewLeftMenu("school");
				break;
			case "roomEdit":
				$contentField	.= $view->editRoom($room->getRoomData($_GET['id']));
				$leftMenu		.= view::viewLeftMenu("school");
				break;
			
			case "plan":
				$contentField	.= "<h2>Vertretungsplan</h2>";
				$leftMenu		.= view::viewLeftMenu("plan");
				break;
			
			case "roomPlan":
				$contentField	.= $view->viewRoomPlan($room->getRoomList(),$settings);	
				$leftMenu		.= view::viewLeftMenu("plan");
				break;
			
			case "newProxyInform":
				$data = array("teachers"=>$teacher->listAllTeacher(),"subjects"=>$subject->listAllSubject(),"combination"=>$teacher_subject->getComninationID());
				//print_r($data);
				$contentField	.= $view->newProxySet($data);
				$leftMenu		.= view::viewLeftMenu("plan");
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
