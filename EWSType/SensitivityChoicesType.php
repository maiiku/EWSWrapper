<?php
/**
 * Definition of the SensitivityChoicesType type
 * 
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 */

class EWSType_SensitivityChoicesType extends EWSType {
	/**
	 * Specifies normal confidentiality.
	 * 
	 * @var string
	 */
	const NORMAL = 'Normal';
	
	/**
	 * Specifies personal confidentiality.
	 * 
	 * @var string
	 */
	const PERSONAL = 'Personal';
	
	/**
	 * Specifies private confidentiality.
	 * 
	 * @var string
	 */
	const PRIVATESENSITIVITY = 'Private';

	/**
	 * Specifies confidential confidentiality.
	 * 
	 * @var string
	 */
	const CONFIDENTIAL = 'Confidential';	
	
	/**
	 * Constructor
	 */
	public function __construct() {
	} // end function __construct()
} // end class EWSType_SensitivityChoicesType