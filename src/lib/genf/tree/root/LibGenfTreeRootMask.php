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
class LibGenfTreeRootMask
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

    $checkRoot  = '/bdl/masks';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('masks');
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

    $nodeQuery  = '/bdl/masks/*';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $entityName   = $node->getAttribute('entity');

      $checkQuery   = '/bdl/masks/'.$node->nodeName.'[@name="'.$nodeName.'" and @entity="'.$entityName.'"]';
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
   * Index wird nur für die Menübäume erstellt.
   * Nodes und Subtrees werden nur auf Anfrage verwendet
   *
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#createIndex()
   */
  public function createIndex()
  {



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
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function get( $name, $type = null )
  {

    $modelXpath   = $this->tree->getXpath();

    $check        = '/bdl/masks/'.$type.'[@name="'.$name.'"]';
    $nodeList     = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return simplexml_import_dom($nodeList->item(0));
    else
      return null;

  }//end public function get */


  /**
   * @param string $name
   * @param string $source
   * @param string $type
   */
  public function getTemplate( $name, $source, $type = null )
  {

    $modelXpath   = $this->tree->getXpath();

    $check        = '/bdl/masks/'.$type.'[@name="'.$name.'" and @entity="'.$source.'"]';
    $nodeList     = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0)->cloneNode(true);
    else
      return null;

  }//end public function getTemplate */





} // end class LibGenfTreeRootMask
