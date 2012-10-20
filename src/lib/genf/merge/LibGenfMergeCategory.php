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
class LibGenfMergeCategory
  extends LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeCategory
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfMerge $tree
   * @return  LibGenfMergeCategory
   */
  public static function getInstance( $tree )
  {

    if(is_null(self::$instance))
      self::$instance = new LibGenfMergeCategory($tree);

    return self::$instance;

  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// merge logic
////////////////////////////////////////////////////////////////////////////////


 /**
  *
  * layout
  *   o col
  *     + @type
  *     + @align
  * label       see: label
  * description see: description
  * access      see: access
  *
  * @param DomNode $old
  * @param DomNode $newNode
  * @return unknown_type
  */
  public function merge( $oldNode, $newNode )
  {

    foreach( $newNode->childNodes as $childNode )
    {

      switch( $childNode->nodeName )
      {
        default:
        {
          $this->mergeLayout( $oldNode, $childNode );
          break;
        }
        default:
        {
          $this->mergeGenericElement( $oldNode, $childNode );
          break;
        }

      }//end switch

    }

  }//end public function merge


  /**
   * @param DomNode $old
   * @param DomNode $newNode
   */
  public function mergeLayout( $oldNode, $newNode )
  {
    $this->defaultChildMerge( $oldNode, $newNode );
  }//end public function mergeLayout */


}//end class LibGenfMergeCategory
