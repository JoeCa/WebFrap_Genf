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
class LibGenfMergeManagement
  extends LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeManagement
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfMerge $tree
   * @return  LibGenfMergeManagement
   */
  public static function getInstance( $tree )
  {

    if(is_null(self::$instance))
      self::$instance = new LibGenfMergeManagement($tree);

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

    $this->tmpXpath   = new DOMXpath( $newNode->ownerDocument );
    $this->modelXpath = new DOMXpath( $oldNode->ownerDocument );
    $this->oldNode    = $oldNode;
    $this->newNode    = $newNode;

    if( DEBUG )
      Debug::console( 'merge management: '.$oldNode->getAttribute('name') );

    $tmpChilds = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[] = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {

      switch( $childNode->nodeName )
      {
      
        case 'access':
        {
          $this->mergeAccess( $oldNode , $childNode );
          break;
        }
        default:
        {

          $this->mergeGenericElement( $oldNode , $childNode );
          break;

        }

      }//end switch

    }//end foreach( $newNode->childNodes as $childNode )

  }//end public function merge */
  
  
  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeAccess( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './access', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('access');
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }


    foreach( $tmpChilds as $newSub )
    {

      switch( $newSub->nodeName )
      {

        case 'paths':
        {
          
          // importieren des neuen attributes in das full model
          if( $needImport )
            $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);
    
          $newSubName  = $newSub->nodeName;
    
          // check if exist
          $queryCheckExist  = "./{$newSubName}";
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );
    
          // wenn eine vorhandenes attribut mit dem namen gefunden wurde
          if( !$listOldMatches->length )
          {
            $oldParentNode->appendChild( $newSub );
          }
          else 
          {
            $oldParentNode->replaceChild( $newSub, $listOldMatches->item(0) );
          }
          
          break;
        }

        case 'checks':
        {
          
          // importieren des neuen attributes in das full model
          if( $needImport )
            $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);
    
          $newSubName  = $newSub->nodeName;
    
          // check if exist
          $queryCheckExist  = "./{$newSubName}";
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );
    
          // wenn eine vorhandenes attribut mit dem namen gefunden wurde
          if( !$listOldMatches->length )
          {
            $oldParentNode->appendChild( $newSub );
          }
          else 
          {
            $oldParentNode->replaceChild( $newSub, $listOldMatches->item(0) );
          }
          
          break;
        }
        
        default:
        {
          // importieren des neuen attributes in das full model
          if( $needImport )
            $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);
    
          $newSubName  = $newSub->nodeName;
    
          // check if exist
          $queryCheckExist  = "./{$newSubName}";
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );
    
          // wenn eine vorhandenes attribut mit dem namen gefunden wurde
          if( !$listOldMatches->length )
          {
            $oldParentNode->appendChild( $newSub );
          }
          else 
          {
            $oldParentNode->replaceChild( $newSub, $listOldMatches->item(0) );
          }
          
          break;
        }
      }

    }//end foreach

  }//end protected function mergeAccess */

}//end class LibGenfMergeManagement
