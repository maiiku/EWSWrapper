<?php
/**
 * Definition of the ImportanceChoicesType type
 * 
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 */

class EWSType_ImportanceChoicesType extends EWSType {
	/**
	 * Specifies low priority
	 * 
	 * @var string
	 */
	const LOW = 'Low';
	
	/**
	 * Specifies normal priority
	 * 
	 * @var string
	 */
	const NORMAL = 'Normal';
	
	/**
	 * Specifies high priority
	 * 
	 * @var string
	 */
	const HIGH = 'High';
	
	/**
	 * Constructor
	 */
	public function __construct() {
	} // end function __construct()
} // end class EWSType_ImportanceChoicesType