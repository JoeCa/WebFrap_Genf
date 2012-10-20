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
class LibGenfTreeRootPage
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @var LibGenfTreeRootManagement
   */
  public $rootManagement    = null;

  /**
   * @var array
   */
  public $templates  = array();
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/pages';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('pages');
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

    $nodeQuery  = '/bdl/pages/page';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $checkQuery   = '/bdl/pages/page[@name="'.$nodeName.'"]';
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
    
    
    // laden der master templates
    
    $nodeQuery  = '/bdl/pages/master_template';
    $nodeList   = $tmpXpath->query( $nodeQuery );


    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $checkQuery   = '/bdl/pages/master_template[@name="'.$nodeName.'"]';
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
    $nodeList       = $modelXpath->query('/bdl/pages/page');

    if(!$className  = $this->builder->getNodeClass('Page'))
    {
      throw new LibGenfTree_Exception( 'Got no Node for Page' );
    }

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // parse Names
      $name     = $this->parseNames( $smplNode );

      // create an entity index in the node
      $this->nodes[trim($smplNode['name'])] = new $className($smplNode, $name);

    }//end foreach
    
    
    // create index template
    $tplList      = $modelXpath->query('/bdl/pages/master_template');

    if(!$className  = $this->builder->getNodeClass('PageMaster'))
    {
      throw new LibGenfTree_Exception( 'Got no Node for Page' );
    }

    foreach( $tplList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // create an entity index in the node
      $this->templates[trim($smplNode['name'])] = new $className($smplNode);

    }//end foreach

  }//end public function createIndex */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param SimplexmlElement $node
   * @return void
   */
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
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfEnvManagement
   */
  public function createEnv( $page )
  {

    $environment = new LibGenfEnvPage($this->builder,$page);

    return $environment;

  }//end public function createEnv */

  /**
   * @param LibGenfTreeNodeSubpage $page
   * @return LibGenfEnvSubpage
   */
  public function createSubEnv( $page )
  {

    $environment = new LibGenfEnvSubpage($this->builder,$page);

    return $environment;

  }//end public function createSubEnv */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRootProcess#get()
   */
  public function get( $name, $type = null )
  {
    
    $check    = '/bdl/pages/page[@name="'.$name.'"]';
    $nodeList = $this->search->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */



} // end class LibGenfTreeRootPage
