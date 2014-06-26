<?php
/* EWSWrapper Bootstrap
 * ====================================================
 * @author Michal Korzeniowski <mko_san@lafiel.net>
 * @version 0.1
 * @date 08-2011
 * @website http://ewswrapper.lafiel.net/
 * ====================================================
 * Desciption
 * Autoload php-EWS classes
 * 
 * ==================================================*/
/**
 * Load Core Classes
 */
 
require "EWS_Exception.php";
include "EWSType.php";
include "NTLMSoapClient.php";
include "NTLMSoapClient/Exchange.php";
/**
 * Load All Types
 */
foreach ( glob( dirname(__FILE__) . "/EWSType/*.php" ) as $filename ) {
    include $filename ;
}
