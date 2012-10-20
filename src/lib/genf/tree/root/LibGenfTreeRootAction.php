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
class LibGenfTreeRootAction
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

    Debug::console( "in preProcessing actions" );

    $checkRoot  = '/bdl/actions';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('actions');
      $this->nodeRoot = $this->modelRoot->appendChild( $this->nodeRoot );
    }

  }//end public function preProcessing */

  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile(  $tmpXml, $tmpXpath, $repoPath = null  )
  {

    if( !isset( $this->nodeRoot->ownerDocument ) )
    {
      Debug::console( 'Broken Root '.Debug::dumpToString( $this->nodeRoot, true  ) ) ;
      return;
    }

    /*
    $this->builder->interpreter->interpret( $tmpXml, $tmpXml, $tmpXpath );
    $tmpXpath   = new DOMXpath($tmpXml);
    */
    $this->builder->activRepo = $repoPath;

    $nodeQuery  = '/bdl/actions/action';
    $nodeList   = $tmpXpath->evaluate( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $checkQuery   = '/bdl/actions/action[@name="'.$nodeName.'"]';
      $oldNodeList  = $modelXpath->evaluate($checkQuery);

      if( $oldNodeList->length )
      {

        $oldNode = $oldNodeList->item(0);
        $this->merge( $oldNode, $node );

      }//end if
      else
      {
        $importedNode = $this->nodeRoot->ownerDocument->importNode( $node , true );
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
    $modelXpath     = $this->tree->getXpath( );
    $nodeList       = $modelXpath->query( '/bdl/actions/action' );


    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom( $node );
      
      if( !isset($smplNode['type']) )
      {
        $this->builder->error( "Got Action without Type" );
        continue;
      }
      
      $typeKey = SParserString::subToCamelCase( trim($smplNode['type']) );
      
      if( !$className  = $this->builder->getNodeClass( 'Action_'.$typeKey ) )
      {
        $this->builder->error( "Requested nonexisting ActionNode Type {$typeKey}" );
        continue;
      }

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
    $check    = '/bdl/actions/action[@name="'.$name.'"]';
    $nodeList = $this->search->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */
  
  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRootProcess#get()
   */
  public function getAction( $name  )
  {
    
    return isset( $this->nodes[$name] )
      ? $this->nodes[$name]
      : null;
    
  }//end public function get */



} // end class LibGenfTreeRootAction
