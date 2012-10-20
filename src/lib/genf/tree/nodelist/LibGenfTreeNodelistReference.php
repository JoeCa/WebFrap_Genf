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
class LibGenfTreeNodelistReference
  extends LibGenfTreeNodelist
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * list with all many to references
   * @var array
   */
  public $multiRef            = array();

  /**
   * topological sorted list with all one To refernces
   * @var array
   */
  public $singleRef           = array();

  /**
   * flag if the management has multiRef or not
   * @var boolean
   */
  public $hasRef              = false;

  /**
   * the name of the entity from the references
   * @var string
   */
  public $entityName          = null;

  /**
   *
   * @var array
   */
  protected $categories       = array();

////////////////////////////////////////////////////////////////////////////////
// getter + setter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return array
   */
  public function getCategories( )
  {
    return $this->categories;
  }//end public function getCategories */

  /**
   *
   * @return array
   */
  public function getCategoryKeys( )
  {

    return array_keys($this->categories);
  }//end public function getCategoryKeys */

  /**
   *
   * @return array
   */
  public function getCategoryRefs( $name  )
  {

    $refs = array();

    foreach( $this->childs as $child )
    {

      if( $child->category( $name ) )
      {
        $refs[] = $child;
      }

    }

    return $refs;

  }//end public function getCategories */



  /**
   * check if this entity has a specific category
   * @param string $catName
   * @return boolean
   */
  public function inCategory( $catName )
  {
    return isset( $this->categories[$catName] ) ;
  }//end public function inCategory */

  /**
   * @return array
   */
  public function getMultiRefs()
  {
    return $this->multiRef;
  }//end public function getMultiRef */

  /**
   * @return array
   */
  public function getSingleRefs()
  {
    return $this->singleRef;
  }//end public function getSingleRef */
  
  /**
   * @return LibGenfTree
   */
  public function getSingleRef( $key )
  {
    
    return isset($this->singleRef[$key])
      ? $this->singleRef[$key]
      : null;
      
  }//end public function getSingleRef */


  /**
   * @return array
   */
  public function cloneMultiRefs( $management = null )
  {
    $tmp = array();

    foreach( $this->multiRef as $key => $obj )
    {
      $tmp[$key] = clone $obj;
      $tmp[$key]->setManagement($management);
    }

    return $tmp;

  }//end public function getMultiRef */

  /**
   * @return array
   */
  public function cloneSingleRefs( $management = null )
  {
    $tmp = array();

    foreach( $this->singleRef as $key => $obj )
    {
      $tmp[$key] = clone $obj;
      $tmp[$key]->setManagement($management);
    }

    return $tmp;

  }//end public function getSingleRef */

  /**
   * @return array
   */
  public function getReferences()
  {
    return $this->childs;
  }//end public function getSingleRef */

  /**
   * @return array
   */
  public function getReferenceKeys()
  {
    return array_keys($this->childs);
  }//end public function getReferenceKeys */

  /**
   * Enter description here...
   *
   * @param string $name
   * @return unknown
   */
  public function getReference( $name )
  {
    
    if( '' == trim($name) )
    {
      $this->builder->warn
      ( 
        'Entity: '.$this->entityName .' requested reference with empty name string: '.$name.' in '
          .Debug::backtrace(). implode( array_keys($this->childs),' '.NL ).NL.NL 
      );
      return null;
    }

    if( isset($this->childs[trim($name)])  )
    {
      return $this->childs[trim($name)];
    }
    
    /*
    if(DEBUG)
      Debug::console( 'entity: '.$this->entityName .' requested nonexisting reference: '.$name.' in '.Debug::getCallerPosition(), implode( array_keys($this->childs),' ' )  );
    */

    $this->builder->warn
    ( 
      'Entity: '.$this->entityName .' requested nonexisting reference: '.$name.' in '
        .Debug::backtrace(). implode( array_keys($this->childs),' '.NL ).NL.NL 
    );

    return null;

  }//end public function getReference */

  /**
   * 
   * @param string $name
   */
  public function referenceExists( $name )
  {
    return isset($this->childs[trim($name)]);
  }//end public function referenceExists */

  /**
   *
   * @return boolean
   */
  public function hasReferences()
  {
    return $this->hasRef;
  }//end public function hasReferences */


  /**
   *
   * @return boolean
   */
  public function hasCategories()
  {
    return isset( $this->node->categories );
  }//end public function hasCategories */


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param array $params
   */
  protected function parseParams( $params )
  {

    if(!isset($params['name']))
    {
      Error::report('Invalid Treenodelist Creation');
      return;
    }

    $this->entityName = $params['name'];

  }//end protected function parseParams */



  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNodelist#extractChildren($node)
   */
  public function extractChildren( $node )
  {

    if( is_array($node) )
    {
      Debug::console('unexpected inputtype array');
      //$this->extractChildrenArray( $node );
    }
    else
    {
      $this->extractChildrenObject( $node );
    }

    $this->importCategories( );

  }//end public function extractChildren */

  /**
   * @param SimpleXmlElement $node
   *
   */
  public function extractChildrenObject( $node )
  {

    // first clean
    $this->multiRef   = array();
    $this->singleRef  = array();
    $this->childs     = array();
    $this->hasRef     = false;

    if( !isset($node->references->ref) )
      return;

    $this->node   = $node->references;

    // get the entity rootnode to be able to remove nonexisting entities
    ///TODO check for existing entity or for existing target here
    $rootNode       = $this->builder->tree->getRootNode('Entity');
    $refNodeClass   = $this->builder->getNodeClass('Reference');

    $dependencies = array();

    foreach( $this->node->ref as $refNode )
    {
      //if( (string)$refNode['type'] == 'pre' )

      // add default refname and ceck if the node is valid
      if(!isset($refNode->src))
      {
        $refNode->addChild('src');
        $refNode->src->addAttribute('name',$this->entityName);

        if( !isset($refNode->target) || !isset($refNode->target['id']) )
        {
          Error::report
          (
            'got invalid reference in management: '.$this->entityName.' missing all references',
            $refNode
          );
          continue;
        }

      }
      else
      {
        if( !isset($refNode->target) || !isset($refNode->target['name']) )
        {
          Error::report
          (
            'got invalid reference in management: '.$this->entityName.' missing target reference'
          );
          continue;
        }

        // if the target table not exist drop the reference here
        $targetKey = trim($refNode->target['name']);
        if( !$rootNode->exists($targetKey) )
        {
          Debug::console( 'skipping Reference '.(string)$refNode['name'].' cause target: '.trim($refNode->target['name']).' not exists' );
          continue;
        }

        if( !isset($refNode->src['id']) && !isset($refNode->target['id']) )
        {
          Error::report
          (
            'got invalid reference in management: '.$this->entityName.' no ids'
          );
          continue;
        }
      }

      /* the system should create connections tables automatically, this check is obsolete... hopefully
      // check if this is a many to many connection
      // normaly connection should have a name, the treebuilder should make that shure
      if( isset( $refNode->connection ) )
      {
        $connectionKey = trim( $refNode->connection['name'] );

        if( !$rootNode->exists($connectionKey) )
          continue;
      }
      */

      $refObj = new $refNodeClass($refNode);

      // sort is only nessecary for one to references, caus this are the only
      // references that are saved together
      if( $this->isOneToRef( $refNode ) )
      {
        if( $this->preSave( $refNode )  )
        {
          $child  = (string)$refNode->src['name'];
          $father = (string)$refNode['name'];
        }
        else
        {
          $father = (string)$refNode->src['name'];
          $child  = (string)$refNode['name'];
        }

        $dependencies[] = array( $child, $father  );

      }
      else
      {
        // multi refs can just be appended
        $this->multiRef[] = $refObj;
      }

      $this->childs[(string)$refNode['name']] =  $refObj;

    }//end foreach

    try
    {
      $deps = new LibDependency( $dependencies, true, $this->entityName );
      $tmp  = SParserArray::multiDimFusion($deps->solveDependencies());

      // only add references but not the source table
      foreach( $tmp as $key )
      {
        if( isset( $this->childs[$key] ) )
        {
          $this->singleRef[$this->childs[$key]->name->name] = $this->childs[$key];
        }
      }

      $this->hasRef = true;
    }
    catch( LibException $e )
    {
      throw new LibParser_Exception( $e->getMessage().' in Node '.$this->entityName );
      $this->hasRef  = false;
    }

  }//end public function extractChildren */



/*//////////////////////////////////////////////////////////////////////////////
// protected methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Enter description here...
   *
   * @param string $ref
   * @return unknown
   */
  protected function preSave( $ref )
  {

    // check if preSave is defined by the user
    if( isset( $ref['base'] ) )
    {
      return ('src' === trim($ref['base']));
    }

    // else guess
    if
    (
      ! isset( $ref['srcId'] ) // has no attribute srcId
        || '' === trim($ref['srcId']) // or it's empty
        || trim($ref['srcId']) == $this->builder->rowidKey // or is rowid
    )
    {
      return true;
    }
    else
    {
      return false;
    }

  }//end public function preSave */

  /**
   * Enter description here...
   *
   * @param string $ref
   * @return unknown
   */
  protected function isOneToRef( $ref )
  {

    return ( 'one' === substr( strtolower($ref['relation']),0,3  ) );

  }//end public function isOneToRef */



  /**
   *
   */
  protected function importCategories( )
  {

    $categoryClass = $this->builder->getNodeClass('Category');

    if( isset( $this->node->categories) )
    {

      $cats = $this->node->categories;

      foreach( $cats->category as $category )
      {

        $name = (string)$category['name'];

        /*
          $label  = (string)$category['label'];
          $type   = isset($category['type']) ? (string)$category['type']:'2';
        */

        if( !isset($this->categories[$name]) )
          $this->categories[$name] = new $categoryClass( $category );

      }//end foreach

    }//end if

    // check that every category from all references is in the category list
    if(isset( $this->node->ref ))
    {
      foreach( $this->node->ref as $ref )
      {

        if( isset($ref->category['name']) )
        {
          $name = (string)$ref->category['name'];

          if( !isset($this->categories[$name]) )
            $this->categories[$name] = new $categoryClass( $name );
        }

      }//end foreach
    }

  }//end protected function importCategories */

}//end class LibGenfTreeNodelistReference

