<?php
/* EWSWrapper Test & Example usage file
 * ====================================================
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 * @version 0.1
 * @date 08-2011
 * @website http://ewswrapper.lafiel.net/
 * ====================================================
 * Desciption
 * Provides example usage of EWSWrapper
 * To use simple set $to_test to desired action
 * Enumartation of action list in the switch:
 * - calendar_add
 * - calendar_edit
 * - calendar_list
 * - calendar_delete
 * - task_add
 * - task_edit
 * - task_list
 * - task_delete
 * - list_folders
 * 
 * ==================================================*/
 
//include EWS wrapper
include "EWSWrapper.php";
//set your timezone
date_default_timezone_set('Europe/Warsaw');

//user, password & host setup
$host = "red002.mail.emea.microsoftonline.com";
$username = "test@test.com";
$password = "password";

//what would you like to test tonight?
$to_test = 'list_folders';

//create EWSWrapper object
$ews = new EWSWrapper($host, $username, $password);

switch ($to_test){
    //test event adding
    case 'calendar_add':
	$response = $ews->addCalendarEvent('Test subject', 
		       "Test desription", 
		       null, 
		       strtotime('noon + 1 hour'),
		       strtotime('noon + 2 hours'), 
		       "Test subject", 
		       array('email1@test.com',
					 'email2@test.com',
					 'email3@test.com')
		     );
	print_r($response);
	die();
	break;

    //test event editing
    case 'calendar_edit':
	$response = $ews->editCalendarEvent(
			"AAMkADMyZmU2MWY4LWFiYTQtNDI1MC05Njg4LTZlYTNhZDEyMTcyZgBGAAAAAACrLZ0u9XUlTblYQop7sEBsBwA1mZ7/RHM2TJiZHLRlnhdzAAANH+9gAAA1mZ7/RHM2TJiZHLRlnhdzAAYyRuOFAAA=", 
			"DwAAABYAAAA1mZ7/RHM2TJiZHLRlnhdzAAYyUBK6", 
			"Sample title",
			"Sample description", 
			"TEXT",
			strtotime("3 PM"), 
			strtotime("4 PM")		    
		    );
	print_r($response);
	die();    
	break;
    //test event listing
    case 'calendar_list':
	$response = $ews->listCalendarEvent(
			null, 
			mktime(0, 0, 0, 9, 31, 2011), 
			time()
		    );   
	print_r($response);
	die();       
	break;
    //test event deleteing
    case 'calendar_delete':
	$response = $ews->deleteCalendarEvent(array(
			$ids="AAMkADMyZmU2MWY4LWFiYTQtNDI1MC05Njg4LTZlYTNhZDEyMTcyZgBGAAAAAACrLZ0u9XUlTblYQop7sEBsBwA1mZ7/RHM2TJiZHLRlnhdzAAANH+9gAAA1mZ7/RHM2TJiZHLRlnhdzAAYyRuOFAAA="
	));
	print_r($response);
	die();    
	break;
    //test task adding
    case 'task_add':
	$response = $ews->addTask(
			"Very Important Task",
			'email1@test.com',
			time(),
			"Sample task description",
			(time()+3600)
		    );
	print_r($response);
	die(); 	
	break;
    //test task editing
    case 'task_edit':
	$response = $ews->editTask(
			"AAMkAGRkNzBkYTNlLTg2NjEtNDA5YS1iOWU3LTY0YWRhZTYzMmUwMgBGAAAAAABVA5BhgjxARYSIPL1mPSAUBwAYjaWDBdsHQo5pvIdFf3S+AAAEQYW3AAAYjaWDBdsHQo5pvIdFf3S+AB9a+kPVAAA=", 
			"EwAAABYAAAAYjaWDBdsHQo5pvIdFf3S+AB9a+2cq", 
			"Updated Subject", 
			"Updated description", 
			"TEXT", 
			(time()+3600),
			(time()+1000), 
			15, 
			"INPROGRESS", 
			10,
			null, 
			"LOW",  
			"Default"			
		    );
	print_r($response);
	die();	
	break;
    //test task listing
    case 'task_list':
	$response = $ews->listTask(
			null, 
			mktime(0, 0, 0, 6, 30, 2010), 
			time(),
			'email1@test.com'
		    );   
	print_r($response);
	die();       
	break;
    //test task deleting
    case 'task_delete':
	$response = $ews->deleteTask(array(
			"AAMkAGRkNzBkYTNlLTg2NjEtNDA5YS1iOWU3LTY0YWRhZTYzMmUwMgBGAAAAAABVA5BhgjxARYSIPL1mPSAUBwAYjaWDBdsHQo5pvIdFf3S+AAAEQYW3AAAYjaWDBdsHQo5pvIdFf3S+AB9a+kPVAAA=", 
		    ));
	print_r($response);
	die();     	
	break;
    //test folder listing
    case 'list_folders':
	$response = $ews->listFolders(
			"INBOX",
			'email1@test.com',
			'DEEP'
		    );
	print_r($response);
	die();     	
	break;	
    default:
	print_r('No known action selected');
}