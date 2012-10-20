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
class LibGenfTreeRootProcess
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @var LibGenfTreeRootManagement
   */
  public $rootManagement    = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/processes';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('processes');
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

    $nodeQuery  = '/bdl/processes/process';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $checkQuery   = '/bdl/processes/process[@name="'.$nodeName.'"]';
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
    
    
    $messageQuery  = '/bdl/processes/process/messages/message';
    $messageList   = $tmpXpath->query( $messageQuery );

    $modelXpath = $this->tree->getXpath();
    
    $messageRoot = $this->tree->getRootNode( 'Message' );

    foreach( $messageList as $node )
    {
      
      $processName = $node->parentNode->parentNode->getAttribute( 'name' );

      $msgName  = $node->getAttribute( 'name' );
      $class    = $node->getAttribute( 'class' );
      $entity   = $node->getAttribute( 'entity' );
      $extends  = $node->getAttribute( 'extends' );
      
      if( !$entity )
        $entity   = $node->parentNode->parentNode->getAttribute( 'src' );
      
      $msgParams = array();
      
      $msgName  = $processName.'-'.$msgName;
      
      if( !$class )
      {
        $class = $msgName;
      }
      
      if( !$extends )
      {
        $extends = $processName.'-base';
      }
    
      $msgParams['class'] = $class;
      
      if( $entity )
        $msgParams['entity'] = $entity;
        
      $msgParams['extends'] = $extends;
      
      $messageRoot->createDefault( $msgName, $msgParams );

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
    $nodeList       = $modelXpath->query('/bdl/processes/process');

    $this->rootManagement     = $this->tree->getRootNode('Management');

    if(!$className  = $this->builder->getNodeClass('Process'))
    {
      throw new LibGenfTree_Exception( 'Got no Node for Process' );
    }

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // create an entity index in the node
      $this->nodes[trim($smplNode['name'])] = new $className($smplNode);

    }//end foreach

  }//end public function createIndex */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param SimplexmlElement $node
   * @return void
   * /
  public function parseNames( $node )
  {

    $name = trim($node['name']);

    if( isset($this->names[$name]) )
      return $this->names[$name];

    $obj = new LibGenfNameProcess
    (
      $node,
      array( 'interpreter' => $this->builder->interpreter  )
    );

    return $obj;

  }//end public function parseNames */

  /**
   * @param string $name
   * @param array $params
   */
  public function createDefault( $name, $params = array() )
  {


  }//end public function createDefault */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRootProcess#get()
   */
  public function get( $name, $type = null )
  {
    $check      = '/bdl/processes/process[@name="'.$name.'"]';
    
    $modelXpath = $this->tree->getXpath( );
    $nodeList   = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */

  /**
   * @param string $name
   * @return LibGenfTreeNodeProcess 
   */
  public function getProcess( $name )
  {
    
    return isset($this->nodes[trim($name)])
      ? $this->nodes[trim($name)]
      : null;

  }//end public function getProcess */

} // end class LibGenfTreeProcess
