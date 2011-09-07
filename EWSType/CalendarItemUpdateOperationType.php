<?php
/**
 * Definition of the CalendarItemUpdateOperationType type
 * 
 * @author James I. Armes <http://www.jamesarmes.net>
 */

class EWSType_CalendarItemUpdateOperationType extends EWSType {
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
	 * Send to
	 *
	 * @var string
	 */
	const SEND_ONLY_TO_CHANGED = 'SendOnlyToChanged';

	/**
	 * Send to
	 *
	 * @var string
	 */
	const SEND_TO_CHANGED_AND_SAVE_COPY = 'SendToChangedAndSaveCopy';

	/**
	 * Constructor
	 */
	public function __construct() {
	} // end function __construct()
} // end class CalendarItemUpdateOperationType