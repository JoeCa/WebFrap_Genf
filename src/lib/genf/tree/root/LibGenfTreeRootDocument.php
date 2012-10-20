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
class LibGenfTreeRootDocument
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/documents';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('documents');
      $this->modelRoot->appendChild( $this->nodeRoot );
    }

  }//end public function preProcessing */

  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile(  $tmpXml, $tmpXpath, $repoPath = null  )
  {

    /*
    $this->builder->interpreter->interpret( $tmpXml, $tmpXml, $tmpXpath );
    $tmpXpath   = new DOMXpath($tmpXml);
    */
    $this->builder->activRepo = $repoPath;

    $nodeQuery  = '/bdl/documents/document';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute( 'name' );
      $checkQuery   = '/bdl/documents/document[@name="'.$nodeName.'"]';
      $oldNodeList  = $modelXpath->query($checkQuery);

      if( $oldNodeList->length )
      {

        $oldNode = $oldNodeList->item(0);
        $this->merge( $oldNode, $node );

      }//end if
      else
      {
        $importedNode = $this->modelTree->importNode( $node , true );
        $this->nodeRoot->appendChild( $importedNode );
      }//end else

    }//end foreach

  }//end public function importFile */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#createIndex()
   */
  public function createIndex()
  {

    // append default attributes to the entity
    $modelXpath     = $this->tree->getXpath();
    $nodeList       = $modelXpath->query( '/bdl/documents/document' );

    if( !$className  = $this->builder->getNodeClass( 'Document' ) )
    {
      throw new LibGenfTree_Exception( 'Got no Node for Document' );
    }

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // create an entity index in the node
      $this->nodes[trim($smplNode['name'])] = new $className( $smplNode );

    }//end foreach

  }//end public function createIndex */


  /**
   * @param string $name
   * @param array $params
   */
  public function createDefault( $name, $params = array() )
  {


  }//end public function createDefault */

  
  /**
   * @param LibGenfTreeNodeDocument $document
   * @return LibGenfEnvDocument
   */
  public function createEnvironment( $document )
  {

    $environment = new LibGenfEnvDocument( $this->builder, $document );

    return $environment;

  }//end public function createEnvironment */
  
  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function get( $name, $type = null )
  {
    
    $modelXpath = $this->tree->getXpath();
    
    $check    = '/bdl/documents/document[@name="'.$name.'"]';
    $nodeList = $modelXpath->query( $check );

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */



} // end class LibGenfTreeRootDocument
