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
 * @package WebFrap
 * @subpackage GenF
 */
class Bdl
{
////////////////////////////////////////////////////////////////////////////////
// constantes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the default amount of default cols
   * @var int
   */
  const DEF_COL_NUMBER = 2;

////////////////////////////////////////////////////////////////////////////////
// Referenz Konstanten
////////////////////////////////////////////////////////////////////////////////
  
  
  const ONE           = 'one';
  
  const MANY          = 'many';

  const ONE_TO_ONE    = 'onetoone';
  
  const ONE_TO_MANY   = 'onetomany';
  
  const MANY_TO_ONE   = 'manytoone';
  
  const MANY_TO_MANY  = 'manytomany';
  
////////////////////////////////////////////////////////////////////////////////
// Controll Konstanten
////////////////////////////////////////////////////////////////////////////////

  /**
   * Filter die Control Elemente haben
   * @var int
   */
  const FILTER_CONTROL = 1;
  
  /**
   * Filter die zwar kein Element haben aber per URL gesteuern werden können
   * @var int
   */
  const FILTER_CONTROL_ABLE = 2;
  
////////////////////////////////////////////////////////////////////////////////
// Controll Konstanten
////////////////////////////////////////////////////////////////////////////////

  const JOIN_WHERE = 5;
  
  const JOIN_ALIAS = 6;
  
  const JOIN_COMMENT = 6;
  
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * BDL Root Node
   * @var BdlRoot
   */
  protected static $bdlRoot = null;
  
////////////////////////////////////////////////////////////////////////////////
// function
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return BdlRoot
   */
  public static function getBdlRoot()
  {
    
    if( !self::$bdlRoot )
      self::$bdlRoot = new BdlRoot();
    
  }//end public static function getBdlRoot */

} // end  class Bdl
