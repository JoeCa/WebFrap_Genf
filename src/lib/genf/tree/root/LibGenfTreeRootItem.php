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
class LibGenfTreeRootItem
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfTreeNodeItem $item
   * @return LibGenfEnvItem
   */
  public function createEnvironment( $item )
  {

    //$environment = new LibGenfEnvItem( $this->builder, $item );

    return new LibGenfEnvItem( $this->builder, $item );

  }//end public function createEnvironment */
  
  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/items';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement( 'items' );
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

    $nodeQuery  = '/bdl/items/item';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {
    
      $newName  = $node->getAttribute( 'name' );
      $newType  = $node->getAttribute( 'type' );
      $newSource  = $node->getAttribute( 'source' );
      $mgmt       = $node->getAttribute( 'class' );
      
      
      if( '' != trim($mgmt) )
      {
        $checkQuery    = '/bdl/items/item[@name="'.$newName.'" and @type="'.$newType.'" and @source="'.$newSource.'" and @class="'.$mgmt.'"]';
      }
      else 
      {
        $checkQuery    = '/bdl/items/item[@name="'.$newName.'" and @type="'.$newType.'" and @source="'.$newSource.'"]';
      }

      $nodeName     = $node->getAttribute( 'name' );
      $oldNodeList  = $modelXpath->query( $checkQuery );

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
    $nodeList       = $modelXpath->query( '/bdl/items/item' );

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);
      $itemClass = 'Item'.SParserString::subToCamelCase( trim($smplNode['type']) );
      
      if( !$className  = $this->builder->getNodeClass( $itemClass ) )
      {
        throw new LibGenfTree_Exception( 'Got no Node for '.$itemClass );
      }
    
      $itemclass = '';
      if( isset($smplNode['class']) )
        $itemclass  = '-'.trim( $smplNode['class'] );
      
      $key = trim($smplNode['name']).'-'.trim($smplNode['type']).'-'.trim($smplNode['source'].$itemclass);
      
      // create an entity index in the node
      $this->nodes[$key] = new $className( $smplNode );

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
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRootItem#get()
   */
  public function get( $name, $type = null )
  {

    $check    = '/bdl/items/item[@name="'.$name.'"]';
    
    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */

  /**
   * Erfragen des TreeNodes
   * @param SimpleXmlElement $node
   * @return LibGenfTreeNodeItem
   */
  public function getTreeNode( $node )
  {
    
    $itemName   = trim( $node['name'] );
    $itemType   = trim( $node['type'] );
    $itemSource = trim( $node['source'] );
    
    $itemclass = '';
    if( isset( $node['class'] ) )
      $itemclass  = '-'.trim( $node['class'] );
    
    $key = $itemName.'-'.$itemType.'-'.$itemSource.$itemclass;

    if( isset( $this->nodes[$key] ) )
      return $this->nodes[$key];
    else 
      return null;
    
  }//end public function getTreeNode */
  
  /**
   * Erfragen des TreeNodes
   * @param SimpleXmlElement $node
   * @return LibGenfTreeNodeItem
   */
  public function getItem( $node )
  {
    
    $itemName   = trim( $node['name'] );
    $itemType   = trim( $node['type'] );
    $itemSource = trim( $node['source'] );
    
    $itemclass = '';
    if( isset($node['class']) )
      $itemclass  = '-'.trim( $node['class'] );
    
    $key = $itemName.'-'.$itemType.'-'.$itemSource.$itemclass;

    if( isset( $this->nodes[$key] ) )
      return $this->nodes[$key];
    else 
      return null;
    
  }//end public function getItem */
  
  /**
   * Erfragen des TreeNodes
   * @param SimpleXmlElement $node
   * @return LibGenfTreeNodeItem
   */
  public function dumpKeys( )
  {
    
    $this->builder->error( "ITEMS ".NL. implode(','.NL, array_keys($this->nodes)) );
    
  }//end public function dumpKeys */

  /**
   * @param string|SimpleXmlElement
   */
  public function addItemNode( $node )
  {
    
    if( !is_object( $node ) )
      $node = $this->stringToNode( $node );
      
    $newName  = $node->getAttribute( 'name' );
    $newType  = $node->getAttribute( 'type' );
    $newSource  = $node->getAttribute( 'source' );
    $mgmt       = $node->getAttribute( 'class' );
    
    
    if( '' != trim($mgmt) )
    {
      $check    = '/bdl/items/item[@name="'.$newName.'" and @type="'.$newType.'" and @source="'.$newSource.'" and @class="'.$mgmt.'"]';
    }
    else 
    {
      $check    = '/bdl/items/item[@name="'.$newName.'" and @type="'.$newType.'" and @source="'.$newSource.'"]';
    }
    
    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query( $check );

    // create entities, if not yet exists
    if( !$nodeList->length )
    {
      if( $node->ownerDocument !== $this->nodeRoot->ownerDocument )
      {
        $node = $this->nodeRoot->ownerDocument->importNode( $node , true );
      }
      $this->nodeRoot->appendChild( $node );
    }

  }//end public function addItemNode */

} // end class LibGenfTreeRootItem
