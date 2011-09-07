<?php
/**
 * Definition of the AffectedTaskOccurrencesType type
 * 
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 */


class EWSType_AffectedTaskOccurrencesType extends EWSType {
	/**
	 * Specifies that a DeleteItem request deletes the master task, and therefore all recurring tasks that are associated with the master task.
	 *
	 * @var string
	 */
	const ALL_OCCURRENCES = 'AllOccurrences';

	/**
	 * Specifies that a DeleteItem request deletes only the current occurrence of a task.
	 *
	 * @var string
	 */
	const SPECIFIED_OCCURRENCES_ONLY = 'SpecifiedOccurrenceOnly';


	/**
	 * Constructor
	 */
	public function __construct() {
	} // end function __construct()
} // end class CalendarItemCreateOrDeleteOperationType