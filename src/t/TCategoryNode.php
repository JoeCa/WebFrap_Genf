<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <dominik.bonsch@webfrap.net>
* @date        :
* @copyright   : Webfrap Developer Network <contact@webfrap.net>
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/


/**
 * Ein Array Objekt für Simple Daten
 * @package WebFrap
 * @subpackage GenF
 */
class TCategoryNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  public $name    = null;

  public $type    = null;

  public $code    = array();

  public $top     = array();

  public $bottom  = array();

  public $hidden  = array();

  /**
   *
   */
  public function __construct( $name , $type = 2 )
  {
    $this->name = $name;
    $this->type = $type;
  }//end public function __construct

}//end class TCategoryNode


