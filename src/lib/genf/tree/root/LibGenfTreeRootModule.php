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
class LibGenfTreeRootModule
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var null
   */
  public $module    = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot = '/bdl/modules';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('modules');
      $this->modelRoot->appendChild( $this->nodeRoot );
    }

  }//end public function preProcessing */

  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile( $tmpXml, $tmpXpath, $repoPath = null  )
  {

    $this->builder->activRepo = $repoPath;

    $this->builder->interpreter->interpret( $tmpXml, $tmpXml, $tmpXpath );

    $tmpXpath   = new DOMXpath($tmpXml);

    $nodeQuery  = '/bdl/modules/module';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {
      $this->add( $node );
    }//end foreach

  }//end public function importFile */

  /**
   * create the index for this node
   * @return boolean
   */
  public function createIndex()
  {

    $query = '/bdl/modules/module';

    $modelXpath = $this->tree->getXpath();
    $list       = $modelXpath->query( $query );

    if(!$className = $this->builder->getNodeClass('Module'))
    {
      throw new LibGenfTree_Exception( 'Got no Node for Module' );
    }

    foreach( $list as $node )
    {

      $smplNode   = simplexml_import_dom($node);

      $nodeName = trim($smplNode['name']);

      // parse Names
      $this->parseNames( $smplNode );

      // create an entity index in the node
      $newNode = new $className( $smplNode );
      $newNode->name = $this->names[$nodeName];

      $this->nodes[$nodeName] = $newNode;

    }//end foreach


  }//end public function createIndex */

  /**
   * @param string $name
   * @return void
   */
  public function createDefault( $name, $params = array() )
  {

    if( $this->get($name) )
      return true;

    $xml = <<<CODE
  <module name="{$name}" >

  </module>
CODE;

    $this->stringToNode( $xml , $this->nodeRoot );

    return true;

  }//end public function createDefault */

  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param string $name
   * @param string $label
   * @return void
   */
  public function parseNames( $node  )
  {

    $name       = trim($node['name']);

    if( isset($this->names[$name]) )
      return $this->names[$name];

    $obj = new LibGenfName( $node );

    $obj->name      = ucfirst($name);
        
    $label      = $this->builder->interpreter->getLabel( $node );
    
    if( $label )
      $obj->label   = $label;
    else
      $obj->label   = ucfirst($name);
      
    if( trim($obj->label) == '' )
      $obj->label   = ucfirst($name);
    

    $this->names[$name]       = $obj;
    
    return $obj;

  }//end public function parseNames */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNode#get()
   */
  public function get( $name, $type = null )
  {

    $nodeQuery  = '/bdl/modules/module[@name="'.$name.'"]';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($nodeQuery);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */

  /**
   * @param string $name
   * @return SimpleXmlElement
   */
  public function getModule( $name )
  {

    if( isset( $this->nodes[$name] ) )
      return $this->nodes[$name];
    else
      return null;

  }//end public function getModule */

  /**
   * set entity activ
   *
   * @param string $name
   */
  public function setActiv( $name )
  {

    if( is_object($name) )
    {
      if( $name instanceof LibGenfTreeNodeModule )
      {
        $name = $name->name();
      }
      else
      {
        $name = trim($name['name']);
      }
    }


    if( isset($this->nodes[$name]) )
    {
      $this->module = $this->nodes[$name];
    }
    else
    {
      $this->module = null;
      Log::warn( 'Failed to set Module: '.$name.' activ cause of missing module object' );
      return false;
    }

    if( isset($this->names[$name]) )
    {
      $this->name = $this->names[$name];
    }
    else
    {
      $this->name = null;
      Log::warn( 'Failed to set Module: '.$name.' activ cause of missing name object' );
      return false;
    }

    return true;

  }//end public function setActiv */

} // end class LibGenfTreeModule
