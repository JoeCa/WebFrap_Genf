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
class LibGenfMergeModule
  extends LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeModule
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfMerge $tree
   * @return  LibGenfMergeEntity
   */
  public static function getInstance( $tree )
  {

    if(is_null(self::$instance))
      self::$instance = new LibGenfMergeModule($tree);

    return self::$instance;

  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// merge logic
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DomNode $old
   * @param DomNode $newNode
   * @return DomNode
   */
  public function merge( $oldNode, $newNode  )
  {

    if( $oldNode->ownerDocument !== $newNode->ownerDocument )
    {
      $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);
    }

    $oldNode->parentNode->replaceChild($newNode,$oldNode);

  }//end public function merge */

}//end class LibGenfMergeEntity
