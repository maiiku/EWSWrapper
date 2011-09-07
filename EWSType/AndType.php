<?php
/**
 * Definition of the AndType type
 * 
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 */

class EWSType_AndType extends EWSType {
	/**
	 * SearchExpression property
	 * 
	 * @var EWSType_MultipleOperandBooleanExpressionType
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
				'type' => 'MultipleOperandBooleanExpressionType',
			),		    
		); // end $this->schema
	} // end function __construct()
} // end class AndType