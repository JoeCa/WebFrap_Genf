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
 * Hilfsklasse zum beschreiben der Datenstructur zum generieren von Joins
 * 
 * @package WebFrap
 * @subpackage WebFrap
 */
class TTabJoin
{

  public $table     = null;
  
  public $joins     = array();
  
  public $index     = array();
  
  public $deployed  = array();
  
}//end class TTabJoin

