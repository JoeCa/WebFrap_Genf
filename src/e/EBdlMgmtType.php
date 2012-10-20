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
 * @subpackage Pontos
 */
class EBdlMgmtType
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var int
   */
  const DEF     = 'default';

  /**
   * @var int
   */
  const VIEWER  = 'viewer';

  
  /**
   * @var array
   */
  public static $labels = array
  (
    self::DEF     => 'Default',
    self::VIEWER  => 'Viewer',
  );
  
  /**
   * @param string $key
   * @return string
   */
  public static function label( $key )
  {
    
    return isset( self::$labels[$key] ) 
      ? self::$labels[$key]
      : self::$labels[self::DEF];
      
  }//end public static function label */

}//end class EBdlMgmtType

