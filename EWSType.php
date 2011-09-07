<?php
/**
 * Base class for Exchange Web Service Types
 * 
 * @author James I. Armes <http://www.jamesarmes.net>
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 * 
 */

abstract class EWSType {
	/**
	 * Schema definition for the type object
	 * 
	 * @var array
	 */
	protected $schema = array();

	/**
	 * Constructor
	 */
	public abstract function __construct();

	/*
	 According to specific of organization process of SOAP class in PHP5, 
	 * we must wrap up complex objects in SoapVar class. Otherwise objects 
	 * would not be encoded properly and could not be loaded on remote 
	 * SOAP handler.
	 *
	*/	
	public function getAsSOAP() {
	    $this->recursive_unset($this,"schema");
	    //print_r($this);
	     foreach($this as $key=>&$value) {
		$this->prepareSOAPrecursive($this->$key);
	     }
	     return $this;
	}

	private function prepareSOAPrecursive(&$element) {
	    if(is_array($element)) {
		foreach($element as $key=>&$val) {
		    $this->prepareSOAPrecursive($val);
		}
		$element=new SoapVar($element,SOAP_ENC_ARRAY);
		$element = $element->enc_value;
	    }elseif(is_object($element)) {
		if($element instanceof SOAPable) {
		    $element->getAsSOAP();
		}
		$element=new SoapVar($element,SOAP_ENC_OBJECT);
		$element = $element->enc_value;
	    }
	}

	/*
	 *  Recursively unset field from array or object
	 */
	private function recursive_unset(&$object, $unwanted_key) {
	    if(is_object($object)){
		unset($object->$unwanted_key);
		foreach ($object as &$value) {
		    if (is_object($value) || is_array($value)) {
			$this->recursive_unset($value, $unwanted_key);
		    }
		}	    
	    }elseif(is_array($object)){
		unset($object[$unwanted_key]);
		foreach ($object as &$value) {
		    if (is_array($value) || is_object($value)) {
			$this->recursive_unset($value, $unwanted_key);
		    }
		}
	    }
	}    
	
} // end class EWSType