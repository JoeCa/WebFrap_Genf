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
class LibGenfTreeRootDefinition
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot = '//definitions';

    $nodeList = $this->search->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->tree->createElement('definitions');
      $this->root->appendChild( $this->nodeRoot );
    }

  }//end public function preProcessing */

  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile(  $tmpXml, $tmpXpath, $repoPath = null  )
  {

    $this->builder->activRepo = $repoPath;

    $query      = '//definitions/definition';

    $tmpXpath   = new DOMXpath($tmpXml);
    $listNew    = $tmpXpath->query( $query );

    $modelXpath = $this->tree->getXpath();

    foreach( $listNew as $node )
    {
      $modName    = $node->getAttribute('name');
      $checkQuery = '//definitions/definition[@name="'.$modName.'"]';

      $listOld    = $modelXpath->query($checkQuery);

      if( $listOld->length )
      {
        $this->merge( $listOld->item(0), $node );
      }//end if
      else
      {

        $node = $this->modelTree->importNode( $node , true );
        $this->nodeRoot->appendChild( $node );
      }//end else
    }

  }//end public function importFile */

} // end class LibGenfTreeRootDefinition
