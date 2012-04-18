<?php
/* EWSWrapper Class
 * ====================================================
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 * @version 0.12
 * @date 04-2012
 * @website http://ewswrapper.lafiel.net/
 * ====================================================
 * Desciption
 * Provides API wrapper for easy usage of basic tasks 
 * with EWS-PHP:
 * - Calendar events: add, update, delete, list
 * - Taks	    : add, update, delete, list
 * - Messages	    : no support as of yet
 * - Folders	    : list
 * 
 * ==================================================*/
 


Class EWSWrapper {
	 
	//remote host url
	protected $host;
	//mailbox username
	protected $username;
	//mailbox pass
	protected $password;
	//Time Zone settings
	protected $BaseOffset	    = "-P0DT1H0M0.0S";
	protected $Offset	    = "-P0DT2H0M0.0S";
	protected $DaylightTime	    = "02:00:00.0000000";
	protected $StandardOffset   = "-P0DT1H0M0.0S";
	protected $StandardTime	    = "03:00:00.0000000";
	protected $TimeZoneName	    = "(GMT+01:00) Warsaw";	
	protected $ews;
	
	//======================================
	// Constructor
	//======================================
	/* @param string $host  	- event subject
	 * @param string $username     	- event body
	 * @param string $password 	- "on behalf" seneder's email
	 * @param array $timeArr	- override defualt timezone settigns
	 *                              'BaseOffset',
	 * 				'Offset',
	 * 				'DaylightTime',
	 * 				'StandardOffset',
	 * 				'StandardTime',
	 * 				'TimeZoneName'
	 * 
	 */
	public function __construct($host, $username, $password, $timeArr=null){
		
		// inlcude EWS
		include "ExchangeWebServices.php";
		//set deafult timezone
		date_default_timezone_set('Europe/Warsaw');
		
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->ews = new ExchangeWebServices($this->host, $this->username, $this->password, $timeArr);
		
		if (is_array($timeArr)){
			$this->BaseOffset 	= $timeArr['BaseOffset'] 	 ? $timeArr['BaseOffset']    : $this->BaseOffset;
			$this->Offset 		= $timeArr['Offset'] 		 ? $timeArr['Offset']        : $this->Offset;
			$this->DaylightTime 	= $timeArr['DaylightTime'] 	 ? $timeArr['DaylightTime']  : $this->DaylightTime;
			$this->StandardOffset 	= $timeArr['StandardOffset']     ? $timeArr['StandardOffset']: $this->StandardOffset;
			$this->StandardTime 	= $timeArr['StandardTime'] 	 ? $timeArr['StandardTime']  : $this->StandardTime;
			$this->TimeZoneName 	= $timeArr['TimeZoneName'] 	 ? $timeArr['TimeZoneName']  : $this->TimeZoneName;
		}
		
	}

	//======================================
	// Add Calendar Event
	//======================================
	/* @param string $subject  	- event subject
	 * @param int $start 		- event start timestamp
	 * @param int $end		- event end time
	 * @param array $anttendees	- array of email addresses of invited poeople
	 * @param string $body     	- event body
	 * @param string $onbehalf 	- "on behalf" seneder's email
	 * @param string $location	- event loaction
	 * @param bool $allday		- is it an all-day event?
	 * @param string $bodyType	- body format (Text/HTML)
	 * @param string $category	- event actegory
	 * 
	 * @return object response
	 */
	public function addCalendarEvent($subject, $body, $start, $end, array $anttendees, $on_behalf=null, $location=null, $allday = false, $bodyType="TEXT", $category="default"){
		$request = new EWSType_CreateItemType();
                $request->SendMeetingInvitations = 'SendToAllAndSaveCopy';
		$request->SavedItemFolderId->DistinguishedFolderId->Id =EWSType_DistinguishedFolderIdNameType::CALENDAR;
		if($on_behalf)
			$request->SavedItemFolderId->DistinguishedFolderId->Mailbox->EmailAddress = $on_behalf;
		$request->Items->CalendarItem->Subject = $subject;
		$request->Items->CalendarItem->MeetingTimeZone->BaseOffset = $this->BaseOffset;
		$request->Items->CalendarItem->MeetingTimeZone->Daylight->Offset = $this->Offset;
		$request->Items->CalendarItem->MeetingTimeZone->Daylight->Time = $this->DaylightTime ;
		$request->Items->CalendarItem->MeetingTimeZone->Standard->Offset = $this->StandardOffset;
		$request->Items->CalendarItem->MeetingTimeZone->Standard->Time = $this->StandardTime;
		$request->Items->CalendarItem->MeetingTimeZone->TimeZoneName = $this->TimeZoneName;
		if($start)
			$request->Items->CalendarItem->Start = date('c', $start);
		if($end)
			$request->Items->CalendarItem->End = date('c',  $end);
		//making this an all day event
		$request->Items->CalendarItem->IsAllDayEvent = $allday;
		$request->Items->CalendarItem->LegacyFreeBusyStatus = 'Free';
		$request->Items->CalendarItem->Location = $location;
		$request->Items->CalendarItem->Categories->String = $category;
		$request->Items->CalendarItem->Body->BodyType = constant("EWSType_BodyTypeResponseType::".$bodyType);
		$request->Items->CalendarItem->Body->_ = $body;
		for($i = 0; $i < count($anttendees); $i++){
			$request->Items->CalendarItem->RequiredAttendees->Attendee[$i]->Mailbox->EmailAddress = $anttendees[$i];
		}
		//make the call
		$response = $this->ews->CreateItem($request);		
		
		return $response;
	}

	//======================================
	// Edit Calendar Event
	//======================================
	/* @param string $id  		- event id
	 * @param string $ckey  	- event change key
	 * @param string $subject  	- event subject
	 * @param string $body     	- event body
	 * @param int $start 		- event start timestamp
	 * @param int $end		- event end time
	 * @param string $location 	- event location
	 * @param array $anttendees	- array of email addresses of invited poeople
	 * @param bool $allday		- is it an all-day event?
	 * @param string $category	- event actegory
	 * 
	 * @return object response
	 */
	public function editCalendarEvent($id, $ckey, $subject=null, $body=null, $bodytype="TEXT", $start=null, $end=null, $location=null, array $anttendees=array(), $allday=null, $category=null){
                $request = new EWSType_UpdateItemType();
		$updates = array(
			'calendar:Start' =>  date('c', $start),
			'calendar:End'	=> date('c', $end),
			'calendar:Location' => $location,
			'calendar:IsAllDayEvent' => $allday,
			'item:Subject' => $subject,
		);
		
		$request->SendMeetingInvitationsOrCancellations = EWSType_CalendarItemUpdateOperationType::SEND_TO_ALL_AND_SAVE_COPY;
		$request->MessageDisposition = 'SaveOnly';
		$request->ConflictResolution = 'AlwaysOverwrite';
		$request->ItemChanges = new EWSType_NonEmptyArrayOfItemChangesType();
		
		$request->ItemChanges->ItemChange->ItemId->Id = $id;
		$request->ItemChanges->ItemChange->ItemId->ChangeKey = $ckey;
		$request->ItemChanges->ItemChange->Updates = new EWSType_NonEmptyArrayOfItemChangeDescriptionsType();
		
		//popoulate update array
		$n = 0;
		$request->ItemChanges->ItemChange->Updates->SetItemField = array();
		foreach($updates as $furi => $update){
			if($update){
				$prop = array_pop(explode(':',$furi));
				$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = $furi;
				$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->CalendarItem->$prop = $update;
				$n++;
			}
		}
		
		if($attendees){
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = 'calendar:RequiredAttendees';
			for($i = 0; $i < count($attendees); $i++){
				$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->CalendarItem->RequiredAttendees->Attendee[$i]->Mailbox->EmailAddress = $anttendees[$i];
			}
			$n++;	
		}
		if($category){
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = 'item:Categories';
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->CalendarItem->Categories->String = $category;
			$n++;
		}
		if($body){
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = 'item:Body';
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->CalendarItem->Body->BodyType = constant("EWSType_BodyTypeResponseType::".$bodytype);
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->CalendarItem->Body->_ = $body;
			$n++;
		}
		
		//print_r($request); die();
		
		$response = $this->ews->UpdateItem($request);
		
		//$responseCode = $response->ResponseMessages->UpdateItemResponseMessage->ResponseCode;
		//$id = $response->ResponseMessages->UpdateItemResponseMessage->Items->CalendarItem->ItemId->Id;
		//$changeKey = $response->ResponseMessages->UpdateItemResponseMessage->Items->CalendarItem->ItemId->ChangeKey;	
		
		return $response;
	}	
	
	//======================================
	// Delete Calendar Event Items
	//======================================
	/* @param array $ids	  	- array of event ids to delete
	 * 
	 * @return object response
	 */
	public function deleteCalendarEvent(array $ids){
	    return $this->deleteItems($ids);	    
	}		
	
	//======================================
	// List Calendar Events
	//======================================
	/* @param string id 		- item id. takes precendense over timeframe
	 * @param string $onbehalf 	- "on behalf" seneder's email
	 * @param int $start 		- event start timestamp
	 * @param int $end		- event end time
	 * 
	 * @return object response
	 */
	public function listCalendarEvent($id=null, $start=null, $end=null, $onbehalf=null){
		$type = "CALENDAR";
		return $this->listItems($type, $id, $start, $end, $onbehalf);
	}
	
	
	//======================================
	// Add Task
	//======================================
	/* @param string $subject  	- task subject
	 * @param string $body     	- task body
	 * @param string $onbehalf 	- "on behalf" seneder's email
	 * @param int $due 		- task due date timestamp
	 * @param int $reminderdue	- reminder due date timestamp
	 * @param int $reminderStart	- realtive negative offset for reminder start in nimutes
	 * @param string $importance	- task importance
	 * @param string $sensitivity	- task sensitivity
	 * @param string $bodytype	- task body type (TEXT/HTML)
	 * @param string $category	- task category
	 * 
	 * @return object response
	 */	
	public function addTask($subject, $on_behalf, $due, $body=null, $reminderdue=null, $reminderStart="30", $importance="NORMAL", $sensitivity="NORMAL", $bodytype="TEXT", $category="default"){

		$request = new EWSType_CreateItemType();
		$request->SavedItemFolderId->DistinguishedFolderId->Id =EWSType_DistinguishedFolderIdNameType::TASKS;
		if($on_behalf)
			$request->SavedItemFolderId->DistinguishedFolderId->Mailbox->EmailAddress = $on_behalf;
		$request->Items->Task->Subject = $subject;
		if($body){
			$request->Items->Task->Body->BodyType = constant("EWSType_BodyTypeResponseType::".$bodytype);
			$request->Items->Task->Body->_ = $body;
		}
		$request->Items->Task->Sensitivity = constant("EWSType_SensitivityChoicesType::".$sensitivity);
		$request->Items->Task->Categories->String = $category;
		$request->Items->Task->Importance = constant("EWSType_ImportanceChoicesType::".$importance);
		if($reminderdue){
		    $request->Items->Task->ReminderDueBy = date('c',  $reminderdue);
		    $request->Items->Task->ReminderMinutesBeforeStart = $reminderStart;
		    $request->Items->Task->ReminderIsSet = "true";
		}
		$request->Items->Task->DueDate = date('c',  $due);
		
		
		//make the call
		$response = $this->ews->CreateItem($request);		
		
		return $response;

	}

	//======================================
	// Edit Task
	//======================================
	/* @param string $id  		- event id
	 * @param string $ckey  	- event change key
	 * @param string $subject  	- event subject
	 * @param string $body     	- task body
	 * @param string $bodytype	- task body type (TEXT/HTML) 
	 * @param int $due 		- task due date timestamp
	 * @param int $reminderdue	- reminder due date timestamp
	 * @param int $reminderStart	- realtive negative offset for reminder start in nimutes
	 * @param string $status	- task status (enumarted in TaskStatusType)
	 * @param int $percentComplete	- task complitionprocentage
	 * @param string $sensitivity	- task sensitivity (enumarted in SensitivityChoicesType)
	 * @param string $importance	- task importance (enumarted in ImportanceChoicesType)
	 * @param string $category	- task category
	 * 
	 * @return object response
	 */	
	public function editTask($id, $ckey, $subject=null, $body=null, $bodytype=null, $due=null, 
				 $reminderdue=null, $reminderStart=null, $status=null, $percentComplete=null,
				 $sensitivity=null, $importance=null,  $category=null){
		$updates = array(
			'task:DueDate'			  => date('c', $due),
			'task:Status'			  => $status ? constant("EWSType_TaskStatusType::".$status) : null,
			'task:Sensitivity'		  => $sensitivity ? constant("EWSType_SensitivityChoicesType::".$sensitivity) : null,
			'item:Importance'		  => $importance ? constant("EWSType_ImportanceChoicesType::".$importance) : null,
			'item:Subject'			  => $subject,
			'task:PercentComplete'		  => $percentComplete,
			'item:ReminderDueBy'		  => date('c',  $reminderdue),
			'item:ReminderMinutesBeforeStart' => $reminderStart,
			'item:ReminderIsSet'		  => ($reminderdue || $reminderStart) ? true : null, 
		);	
		$request = new EWSType_UpdateItemType();
		$request->MessageDisposition = 'SaveOnly';
		$request->ConflictResolution = 'AlwaysOverwrite';
		$request->ItemChanges = new EWSType_NonEmptyArrayOfItemChangesType();
		
		$request->ItemChanges->ItemChange->ItemId->Id = $id;
		$request->ItemChanges->ItemChange->ItemId->ChangeKey = $ckey;
		$request->ItemChanges->ItemChange->Updates = new EWSType_NonEmptyArrayOfItemChangeDescriptionsType();
		
		//popoulate update array
		$n = 0;
		$request->ItemChanges->ItemChange->Updates->SetItemField = array();
		foreach($updates as $furi => $update){
			if($update){
				$prop = array_pop(explode(':',$furi));
				$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = $furi;
				$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->Task->$prop = $update;
				$n++;
			}
		}

		if($category){
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = 'item:Categories';
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->Task->Categories->String = $category;
			$n++;
		}
		if($body){
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->FieldURI->FieldURI = 'item:Body';
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->Task->Body->BodyType = constant("EWSType_BodyTypeResponseType::".$bodytype);
			$request->ItemChanges->ItemChange->Updates->SetItemField[$n]->Task->Body->_ = $body;
			$n++;
		}
		
		//print_r($request); die();
		
		$response = $this->ews->UpdateItem($request);
		
		//$responseCode = $response->ResponseMessages->UpdateItemResponseMessage->ResponseCode;
		//$id = $response->ResponseMessages->UpdateItemResponseMessage->Items->CalendarItem->ItemId->Id;
		//$changeKey = $response->ResponseMessages->UpdateItemResponseMessage->Items->CalendarItem->ItemId->ChangeKey;	
		
		return $response;	    
	}
	
	//======================================
	// Delete Task Items
	//======================================
	/* @param array $ids	  	- array of taks ids to delete
	 * 
	 * @return object response
	 */
	public function deleteTask(array $ids){
	    return $this->deleteItems($ids);	    
	}	
	
	//======================================
	// List Tasks
	//======================================
	/* @param string id 		- item id; takes precendense over timeframe
	 * @param string $onbehalf 	- "on behalf" task owner's email
	 * @param int $start 		- task search start timestamp
	 * @param int $end		- task serach end timestamp
	 * 
	 * @return object response
	 */	
	public function listTask($id=null, $start=null, $end=null, $on_behalf=null){
		$type = "TASKS";
		return $this->listItems($type, $id, $start, $end, $on_behalf);	    
	}
	
	
	
	//======================================
	// Delete Items
	//======================================
	/* @param array $ids	  	- array of item ids to delete
	 * 
	 * @return object response
	 */
	public function deleteItems(array $ids){
	    $request = new EWSType_DeleteItemType();
	    for($i = 0; $i < count($ids); $i++){
		    $request->ItemIds->ItemId[$i]->Id = $ids[$i];
	    }	    
	    $request->DeleteType = EWSType_DisposalType::MOVE_TO_DELETED_ITEMS;
	    $request->SendMeetingCancellations = EWSType_CalendarItemCreateOrDeleteOperationType::SEND_ONLY_TO_ALL;
	    $request->AffectedTaskOccurrences = EWSType_AffectedTaskOccurrencesType::ALL_OCCURRENCES;
	    $response = $this->ews->DeleteItem($request);
	    
	    return $response;
	}
	
	//======================================
	// List Items
	//======================================
	/* @param string type		- item type
	 * @param string id 		- item id. takes precendense over timeframe
	 * @param string $onbehalf 	- "on behalf": item owner email
	 * @param int $start 		- search start timestamp
	 * @param int $end		- search end timestamp
	 * 
	 * @return object response
	 */
	public function listItems($type, $id=null, $start=null, $end=null, $on_behalf=null){

			
		//by id
		if($id){
			// Form the GetItem request
			$request = new EWSType_GetItemType();		    
			// Define which item properties are returned in the response
			$itemProperties = new EWSType_ItemResponseShapeType();
			$itemProperties->BaseShape = EWSType_DefaultShapeNamesType::DEFAULT_PROPERTIES;
			
			// Add properties shape to request
			$request->ItemShape = $itemProperties;
			
			// Set the itemID of the desired item to retrieve
			$item_id = new EWSType_ItemIdType();
			$item_id->Id = $id;
			$request->ItemIds->ItemId = $item_id;
			
			//  Send the listing (find) request and get the response
			$response = $this->ews->GetItem($request);			
		}
		//by date
		else{
			$request = new EWSType_FindItemType();
			$request->Traversal = EWSType_ItemQueryTraversalType::SHALLOW;	
			
			$request->ItemShape = new EWSType_ItemResponseShapeType();
			$request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::DEFAULT_PROPERTIES;
			
			switch($type){
			    case "CALENDAR" :
				$request->CalendarView = new EWSType_CalendarViewType();
				$request->CalendarView->StartDate = date('c', $start);
				$request->CalendarView->EndDate = date('c', $end);
				break;
			    case "TASKS" :
				//do we have start date?
				$start = $start ? $start : 0;
				//do we have and date?
				$end = $end ? $end : mktime(0, 0, 0, 1, 1, 2038);
				print_r(array($start, $end));
				//create AND restrction
				$request->Restriction = new EWSType_RestrictionType();
				$request->Restriction->And = new EWSType_AndType();
				
				$request->Restriction->And->IsGreaterThanOrEqualTo = new EWSType_IsGreaterThanOrEqualToType();
				$request->Restriction->And->IsGreaterThanOrEqualTo->ExtendedFieldURI = new EWSType_PathToExtendedFieldType;
				$request->Restriction->And->IsGreaterThanOrEqualTo->ExtendedFieldURI->DistinguishedPropertySetId = "Task";
				$request->Restriction->And->IsGreaterThanOrEqualTo->ExtendedFieldURI->PropertyId = "33029";
				$request->Restriction->And->IsGreaterThanOrEqualTo->ExtendedFieldURI->PropertyType = "SystemTime";
				$request->Restriction->And->IsGreaterThanOrEqualTo->FieldURIOrConstant->Constant->Value = date('c', $start);
				
				$request->Restriction->And->IsLessThanOrEqualTo = new EWSType_IsLessThanOrEqualToType();
				$request->Restriction->And->IsLessThanOrEqualTo->ExtendedFieldURI = new EWSType_PathToExtendedFieldType;
				$request->Restriction->And->IsLessThanOrEqualTo->ExtendedFieldURI->DistinguishedPropertySetId = "Task";
				$request->Restriction->And->IsLessThanOrEqualTo->ExtendedFieldURI->PropertyId = "33029";
				$request->Restriction->And->IsLessThanOrEqualTo->ExtendedFieldURI->PropertyType = "SystemTime";
				$request->Restriction->And->IsLessThanOrEqualTo->FieldURIOrConstant->Constant->Value = date('c', $end);
				break;
			}
			// configure the view
			//$request->IndexedPageFolderView = new EWSType_IndexedPageViewType();
			//$request->IndexedPageFolderView->BasePoint = EWSType_IndexBasePointType::BEGINNING;
			//$request->IndexedPageFolderView->Offset = 0;
			/*
			$request->Restriction = new EWSType_RestrictionType();
			$request->Restriction->IsLessThanOrEqualTo = new EWSType_IsLessThanOrEqualToType();
			$request->Restriction->IsLessThanOrEqualTo->ExtendedFieldURI = new EWSType_PathToExtendedFieldType;
			$request->Restriction->IsLessThanOrEqualTo->ExtendedFieldURI->DistinguishedPropertySetId = "Task";
			$request->Restriction->IsLessThanOrEqualTo->ExtendedFieldURI->PropertyId = "33029";
			$request->Restriction->IsLessThanOrEqualTo->ExtendedFieldURI->PropertyType = "SystemTime";
			$request->Restriction->IsLessThanOrEqualTo->FieldURIOrConstant->Constant->Value = date('c', $start);
			*/
			
			$request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
			$request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
			$request->ParentFolderIds->DistinguishedFolderId->Id = constant("EWSType_DistinguishedFolderIdNameType::".$type);
			if($on_behalf)
				$request->ParentFolderIds->DistinguishedFolderId->Mailbox->EmailAddress = $on_behalf;			
	
			//make the call
			//print_r($request); //die();
			$response = $this->ews->FindItem($request);
		}
		
		return $response;
	}

	//======================================
	// List Folders
	//======================================
	/* @param string type		- folder type (enumarted in DistinguishedFolderIdNameType)
	 * @param string $onbehalf 	- "on behalf": item owner email
	 * @param string $depth		- list normal /include subfolders (enumarted in FolderQueryTraversalType)
	 * 
	 * @return object response
	 */
	public function listFolders($type, $on_behalf, $depth = "SHALLOW"){	

	    // start building the find folder request
	    $request = new EWSType_FindFolderType();
	    $request->Traversal = constant("EWSType_FolderQueryTraversalType::".$depth);
	    $request->FolderShape = new EWSType_FolderResponseShapeType();
	    $request->FolderShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;

	    // configure the view
	    $request->IndexedPageFolderView = new EWSType_IndexedPageViewType();
	    $request->IndexedPageFolderView->BasePoint = 'Beginning';
	    $request->IndexedPageFolderView->Offset = 0;

	    // set the starting folder as the inbox
	    $request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
	    $request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
	    $request->ParentFolderIds->DistinguishedFolderId->Id = constant("EWSType_DistinguishedFolderIdNameType::".$type);
		if($on_behalf)
			$request->ParentFolderIds->DistinguishedFolderId->Mailbox->EmailAddress = $on_behalf;
	    
	    // make the actual call
	    $response = $this->ews->FindFolder($request);
	    
	    return $response;

	}

}
