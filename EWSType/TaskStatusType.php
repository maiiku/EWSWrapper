<?php
/**
 * Definition of the TaskStatusType type
 * 
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 */

class EWSType_TaskStatusType extends EWSType {
	/**
	 * Specifies that the task is completed.
	 * 
	 * @var string
	 */
	const COMPLETED = 'Completed';
	
	/**
	 * Specifies that the task is deferred.
	 * 
	 * @var string
	 */
	const DEFERRED = 'Deferred';
	
	/**
	 * Specifies that the task is in progress.
	 * 
	 * @var string
	 */
	const INPROGRESS = 'InProgress';

	/**
	 * Specifies that the task is in progress.
	 * 
	 * @var string
	 */
	const NOTSTARTED = 'NotStarted';	

	/**
	 * Specifies that the task is in progress.
	 * 
	 * @var string
	 */
	const WAITINGONOTHERS = 'WaitingOnOthers';
	
	/**
	 * Constructor
	 */
	public function __construct() {
	} // end function __construct()
} // end class EWSType_TaskStatusType