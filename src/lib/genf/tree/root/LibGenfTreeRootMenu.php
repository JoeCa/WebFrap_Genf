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
class LibGenfTreeRootMenu
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

    $checkRoot  = '/bdl/menus';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('menus');
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

    $nodeQuery  = '/bdl/menus/*';
    $nodeList   = $tmpXpath->query( $nodeQuery );

    $modelXpath = $this->tree->getXpath();

    foreach( $nodeList as $node )
    {

      $nodeName     = $node->getAttribute('name');
      $checkQuery   = '/bdl/menus/'.$node->nodeName.'[@name="'.$nodeName.'"]';
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

    // append default attributes to the entity
    $modelXpath     = $this->tree->getXpath();
    $nodeList       = $modelXpath->query( '/bdl/menus/menu' );

    if( !$className  = $this->builder->getNodeClass( 'Menu' ) )
    {
      throw new LibGenfTree_Exception( 'Got no Node for Menu' );
    }

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // create an entity index in the node
      $this->nodes[trim($smplNode['name'])] = new $className($smplNode);

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
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function get( $name, $type = null )
  {

    $modelXpath     = $this->tree->getXpath();

    $check    = '/bdl/menus/'.$type.'[@name="'.$name.'"]';
    $nodeList = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return simplexml_import_dom($nodeList->item(0));
    else
      return null;

  }//end public function get */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function getMenu( $name, $type = null )
  {

    return $this->get( $name, 'menu'  );

  }//end public function getMenu */
  
  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function getMenuTree( $name, $type = null )
  {

    return $this->get( $name, 'tree'  );

  }//end public function getTree */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function getMenuSubTree( $name, $type = null )
  {

    return $this->get( $name, 'subtree'  );

  }//end public function getMenuSubTree */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#get()
   */
  public function getMenuNode( $name, $type = null )
  {

    return $this->get( $name, 'node'  );

  }//end public function getMenuNode */

  /**
   * @param string $name
   * @param string $type
   * @return LibGenfTreeNode
   */
  public function getGenfNode( $name, $type = null )
  {

    $modelXpath     = $this->tree->getXpath();

    $check    = '/bdl/menus/'.$type.'[@name="'.$name.'"]';
    $nodeList = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $node = simplexml_import_dom($nodeList->item(0));

      switch( $type )
      {
        case 'node':
        {
          $classname   = $this->builder->getNodeClass( 'TreeNode' );
          return new $classname($node);
          break;
        }

        case 'subtree':
        {
          $classname   = $this->builder->getNodeClass( 'TreeSubtree' );
          return new $classname( $node );
          break;
        }

        case 'tree':
        {
          $classname   = $this->builder->getNodeClass( 'Menu' );
          return new $classname( $node );
          break;
        }
        
        case 'menu':
        {
          $classname   = $this->builder->getNodeClass( 'Menu' );
          return new $classname( $node );
          break;
        }

        default:
        {
          $this->builder->warn('Requested unkonw Nodetype : '.$type.' with the Name: '.$name );
        }
      }

    }
    else
    {
      return null;
    }

  }//end public function getGenfNode */


} // end class LibGenfTreeRootMenu
