<?php
/**
 * Definition of the NonEmptyArrayOfSearchExpressionType type
 * 
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 */


class EWSType_NonEmptyArrayOfSearchExpressionType extends EWSType {
	/**
	 * Attendee property
	 * 
	 * @var EWSType_RestrictionType
	 */
	public $SearchExpression;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->schema = array(
			array(
				'name' => 'SearchExpression',
				'required' => false,
				'type' => 'SearchExpressionType',
			),
		); // end $this->schema
	} // end function __construct()
} // end class NonEmptyArrayOfSearchExpressionType