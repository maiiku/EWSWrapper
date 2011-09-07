<?php
/**
 * Definition of the CalendarItemCreateOrDeleteOperationType type
 * 
 * @author James I. Armes <http://www.jamesarmes.net>
 */

/**
 * Definition of the CalendarItemCreateOrDeleteOperationType type
 * 
 * @author James I. Armes <http://www.jamesarmes.net>
 */
class EWSType_CalendarItemCreateOrDeleteOperationType extends EWSType {
	/**
	 * Send to
	 *
	 * @var string
	 */
	const SEND_TO_NONE = 'SendToNone';

	/**
	 * Send to
	 *
	 * @var string
	 */
	const SEND_ONLY_TO_ALL = 'SendOnlyToAll';

	/**
	 * Send to
	 *
	 * @var string
	 */
	const SEND_TO_ALL_AND_SAVE_COPY = 'SendToAllAndSaveCopy';

	/**
	 * Constructor
	 */
	public function __construct() {
	} // end function __construct()
} // end class CalendarItemCreateOrDeleteOperationType