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
class LibGenfMergeDescription
  extends LibGenfMergeLangnode
{
////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeDescription
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfMerge $tree
   * @return  LibGenfMergeDescription
   */
  public static function getInstance( $tree )
  {

    if( is_null(self::$instance) )
      self::$instance = new LibGenfMergeDescription( $tree );

    return self::$instance;

  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// merge logic
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{label} $newNode
   */
  protected function merge( $oldNode, $newNode )
  {

    $this->mergeLang( 'description', $oldNode, $newNode );

  }//end protected function merge */


}//end class LibGenfMergeDescription
