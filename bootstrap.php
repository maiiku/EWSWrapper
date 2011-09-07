<?php
//bootstrap
/**
 * Load Core Classes
 */
include "EWSType.php";
include "NTLMSoapClient.php";
include "NTLMSoapClient/Exchange.php";
/**
 * Load All Types
 */
foreach ( glob( "EWSType/*.php" ) as $filename ) {
    include $filename ;
}