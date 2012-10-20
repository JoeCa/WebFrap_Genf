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
 * class for merging model nodes
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTree
   */
  protected $tree       = null;

  /**
   *
   * @var DomXpath
   */
  protected $modelXpath       = null;

  /**
   *
   * @var DomXpath
   */
  protected $tmpXpath       = null;

  /**
   *
   * @var DomNode
   */
  protected $oldNode       = null;

  /**
   *
   * @var DomNode
   */
  protected $newNode       = null;

  /**
   * @var LibGenfBuild
   */
  protected $builder       = null;

////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////


  /**
   * call the a defined merge action, or the
   * @param string $methodName
   * @param array $params
   * @return mixed
   */
  public function __call( $method, $params )
  {

    if
    (
      'merge' != substr( $method , 0, 5 )
        && 2 === count($params)
        && is_object($params[0]) && $params[0] instanceof DOMNode
        && is_object($params[1]) && $params[1] instanceof DOMNode
    )
    {
      if( method_exists( $this, $method ) )
      {
        return $this->$method($params[0],$params[1]);
      }
      else
      {
        return $this->defaultChildMerge($params[0],$params[1]);
      }
    }
    else
    {
      return null;
    }

  }//end public function __call */


  /**
   *
   * @param LibGenfTree $tree
   */
  public function __construct( $tree )
  {
    $this->tree = $tree;
    $this->init();
  }//end public function __construct */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return LibGenfBuild
   */
  public function getBuilder()
  {
    if(!$this->builder)
      $this->builder = LibGenfBuild::getInstance();

    return $this->builder;
  }//end public function getBuilder */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   */
  protected function init()
  {

  }//end protected function init */


  /**
   * Default merge action if no action is defined yet
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function defaultChildMerge( $oldNode, $newNode )
  {

    $tmpXpath   = new DOMXpath( $newNode->ownerDocument );
    $modelXpath = $this->tree->getXpath();
    $nodeName   = $newNode->nodeName;

    // import node if not yet happend
    if( $oldNode->ownerDocument !== $newNode->ownerDocument )
    {
      $newNode = $oldNode->ownerDocument->importNode($newNode, true);
    }

    //$queryOldNode  = '//'.$nodeName;
    $listOld    = $modelXpath->query( './'.$nodeName , $oldNode );

    // wenn eine vorhandenes attribut mit dem namen gefunden wurde
    try
    {
      if( $listOld->length )
      {
        // das alte mit dem neue ersetzen
        $subNode = $listOld->item(0);
        $oldNode->replaceChild( $newNode, $subNode  );
      }
      else
      {
        // ansonsten das neue attribute anhängen
        $oldNode->appendChild( $newNode );
      }
    }
    catch( Exception $e )
    {
      Debug::console( 'Default Merge for '.$nodeName.' failed '. $e->getMessage() );
    }

  }//end protected function defaultChildMerge */

  /**
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function mergeGenericElement( $oldNode, $newNode )
  {

    /*
    if( DEBUG )
      Debug::console(  'Merge generic Element '.$newNode->nodeName );
    */

    switch( $newNode->nodeName )
    {

      case 'label':
      {
        $this->mergeLabel( $oldNode, $newNode );
        break;
      }
      case 'description':
      {
        $this->mergeDescription( $oldNode, $newNode );
        break;
      }
      case 'info':
      {
        $this->mergeInfo( $oldNode , $newNode );
        break;
      }
      case 'docu':
      {
        $this->mergeDocu( $oldNode , $newNode );
        break;
      }
      case 'categories':
      {
        $this->mergeCategories( $oldNode, $newNode );
        break;
      }
      case 'concepts':
      {
        $this->mergeConcepts( $oldNode, $newNode );
        break;
      }
      case 'data_profile':
      {
        $this->defaultChildMerge( $oldNode, $newNode );
        break;
      }
      case 'access':
      {
        $this->mergeAccess( $oldNode, $newNode );
        break;
      }
      case 'semantic':
      {
        $this->mergeSemantic( $oldNode, $newNode );
        break;
      }
      case 'ui':
      {
        $this->mergeUi( $oldNode, $newNode );
        break;
      }
      default:
      {
        $this->defaultChildMerge( $oldNode, $newNode );
      }

    }//end switch

  }//end protected function mergeGenericElement */

  /**
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function mergeLabel( $oldNode , $newNode )
  {
    LibGenfMergeText::getInstance( $this->tree )->merge( 'label',  $oldNode, $newNode );
  }//end protected function mergeLabel */

  /**
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function mergeDescription( $oldNode , $newNode )
  {
    LibGenfMergeText::getInstance( $this->tree )->merge( 'description', $oldNode, $newNode );
  }//end protected function mergeDescription */

  /**
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function mergeInfo( $oldNode , $newNode )
  {
    LibGenfMergeText::getInstance( $this->tree )->merge( 'info', $oldNode, $newNode );
  }//end protected function mergeDescription */
  
  /**
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function mergeDocu( $oldNode , $newNode )
  {
    LibGenfMergeText::getInstance( $this->tree )->merge( 'docu', $oldNode, $newNode );
  }//end protected function mergeDocu */

  /**
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  protected function mergeUi( $oldNode, $newNode )
  {
    LibGenfMergeUi::getInstance( $this->tree )->merge( $oldNode, $newNode );
  }//end protected function mergeUi */

  
  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeConcepts( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './concepts', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('concepts');
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }


    foreach( $tmpChilds as $newSub )
    {

      // importieren des neuen attributes in das full model
      if($needImport)
        $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);

      $newSubName  = $newSub->getAttribute('name');

      // check if exist
      $queryCheckExist  = './concept[@name="'.$newSubName.'"]';
      $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );

      // wenn eine vorhandenes attribut mit dem namen gefunden wurde
      if( !$listOldMatches->length )
      {
        $oldParentNode->appendChild( $newSub );
      }

    }//end foreach

  }//end protected function mergeConcepts */

  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeCategories( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './categories', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('categories');
      $oldParentNode = $oldNode->appendChild($oldParentNode);

    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }

      
    $catName = $newNode->getAttribute( 'main' );
    
    if( $catName )
      $oldParentNode->setAttribute( 'main', $catName );
    

    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }


    foreach( $tmpChilds as $newSub )
    {

      // importieren des neuen attributes in das full model
      if($needImport)
        $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);

      $newSubName  = $newSub->getAttribute('name');

      // check if exist
      $queryCheckExist  = './category[@name="'.$newSubName.'"]';
      $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );

      // wenn eine vorhandenes attribut mit dem namen gefunden wurde
      if( !$listOldMatches->length )
      {
        $oldParentNode->appendChild( $newSub );
      }

    }//end foreach

  }//end protected function mergeCategories */


  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeDisplay( $oldNode, $newNode )
  {

    //Debug::console('merge display');

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './display', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('display');
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }


    foreach( $tmpChilds as $newSub )
    {

      // importieren des neuen attributes in das full model
      if($needImport)
        $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);

      $newSubName  = $newSub->nodeName;

      // check if exist
      $queryCheckExist  = "./{$newSubName}";
      $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );

      // wenn eine vorhandenes attribut mit dem namen gefunden wurde
      if( !$listOldMatches->length )
      {
        $oldParentNode->appendChild( $newSub );
      }

    }//end foreach

  }//end protected function mergeDisplay */



}//end class LibGenfMergeEntity
