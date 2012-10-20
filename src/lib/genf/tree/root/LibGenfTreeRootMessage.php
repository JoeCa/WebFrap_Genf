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
class LibGenfTreeRootMessage
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    Debug::console( "in preProcessing messages" );

    $checkRoot  = '/bdl/messages';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('messages');
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

    $nodeQuery  = '/bdl/messages/message';
    $nodeList   = $tmpXpath->evaluate( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $checkQuery   = '/bdl/messages/message[@name="'.$nodeName.'"]';
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
    $nodeList       = $modelXpath->query( '/bdl/messages/message' );

    if( !$className  = $this->builder->getNodeClass( 'Message' ) )
    {
      throw new LibGenfTree_Exception( 'Got no Node for Message' );
    }

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom( $node );

      $message = new $className( $smplNode );
      
      
      $mgmt = $this->builder->getManagement( trim($smplNode['entity']) );
      
      if( $mgmt )
      {
        $message->management = $mgmt;
      }
      else
      { 
        $this->builder->error( "Requested nonexisting management ".trim($smplNode['entity'])." for message  ".trim($smplNode['name']) );
      }
      
      // create an entity index in the node
      $this->nodes[trim( $smplNode['name'] )] = $message;

    }//end foreach

  }//end public function createIndex */


  /**
   * @param string $name
   * @param array $params
   */
  public function createDefault( $name, $params = array() )
  {

    if( $this->get( $name ) )
      return true;

    $attributes = '';
    
    if( isset( $params['class'] ) )
      $attributes .= ' class="'.$params['class'].'" ';
    
    if( isset( $params['extends'] ) )
      $attributes .= ' extends="'.$params['extends'].'" ';
      
    if( isset( $params['entity'] ) )
      $attributes .= ' entity="'.$params['entity'].'" ';

    $xml = <<<CODE
  <message name="{$name}"{$attributes} >

  </message>
CODE;


    $this->stringToNode( $xml , $this->nodeRoot );
    
  }//end public function createDefault */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function get( $name, $type = null )
  {
    
    $modelXpath     = $this->tree->getXpath( );
    
    $check    = '/bdl/messages/message[@name="'.$name.'"]';
    $nodeList = $modelXpath->query( $check );

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */



} // end class LibGenfTreeRootMessage
