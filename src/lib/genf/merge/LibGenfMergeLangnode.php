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
class LibGenfMergeLangnode
  extends LibGenfMerge
{

  /**
   * @param string $nodeName
   * @param DomNode{entity} $oldNode
   * @param DomNode{label} $newNode
   */
  protected function mergeLang( $nodeName, $oldNode, $newNode )
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
    $tmpListOld     = $oldXpath->query( './'.$nodeName, $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement( $nodeName );
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $listNewSubnodes      = $newXpath->query( './text', $newNode );

    foreach( $listNewSubnodes as $newSub )
    {

      // importieren des neuen attributes in das full model
      if( $needImport )
        $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);

      $newSubName  = $newSub->getAttribute( $nodeName );

      // check if exist
      $queryCheckExist  = './text[@lang="'.$newSubName.'"]';
      $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );


      // wenn eine vorhandenes attribut mit dem namen gefunden wurde
      if( $listOldMatches->length )
      {
        $oldSubNode = $listOldMatches->item(0);
        $oldParentNode->replaceChild( $newSub, $oldSubNode );
      }
      else
      {
        $oldParentNode->appendChild( $newSub );
      }

    }//end foreach

  }//end protected function merge */

}//end class LibGenfMergeInfo
