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
class LibGenfMergeAttribute
  extends LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeAttribute
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfMerge $tree
   * @return  LibGenfMergeAttribute
   */
  public static function getInstance( $tree )
  {

    if(is_null(self::$instance))
      self::$instance = new LibGenfMergeAttribute($tree);

    return self::$instance;

  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// merge logic
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * display   Dieses Feld identifiziert das Attribute, wobei genau wird mit den folgenden Tags näher spezifiziert, default sind alle
   * search    Nach diesem Attribute soll gesucht werden können
   * unique    enthält internationalisierte Beschreibungen des Attributes
   * description enthält internationalisierte Beschreibungen des Attributes
   * default   Der Standard Wert der Entität
   * uiElement Mit welchen Element wird diese Entität dargestellt
   * categories In welcher Kategory ist dieses Attribute
   * concepts  Liste der Konzepte die auf die Entität angewendet werden können
   * access    Zugriffsberechtigungen für das Attribut
   *
   *
   * @param DomNode $old
   * @param DomNode $newNode
   * @return unknown_type
   */
  public function merge( $oldNode, $newNode  )
  {

    foreach( $newNode->childNodes as $childNode )
    {

      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      switch( $childNode->nodeName )
      {

        case 'display':
        {
          $this->mergeDisplay( $oldNode , $childNode );
          break;
        }
        case 'search':
        {
          $this->mergeSearch( $oldNode , $childNode );
          break;
        }
        case 'unique':
        {
          $this->mergeUnique( $oldNode , $childNode );
          break;
        }
        case 'default':
        {
          $this->mergeDefault( $oldNode , $childNode );
          break;
        }
        case 'uiElement':
        {
          $this->mergeUiElement( $oldNode , $childNode );
          break;
        }
        default:
        {
          $this->mergeGenericElement( $oldNode , $childNode );
          break;
        }

      }//end switch

    }

  }//end public function merge

}//end class LibGenfMergeAttribute
