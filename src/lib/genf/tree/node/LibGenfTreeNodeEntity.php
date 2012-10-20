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
class LibGenfTreeNodeEntity
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * all categories on this entity
   * @var TArray
   */
  protected $categories   = null;

  /**
   * list with all existing attributes
   * @var LibGenfTreenodelistAttribute
   */
  protected $childs       = null;

  /**
   * list with all existing references
   * @var LibGenfTreenodelistReference
   */
  protected $references   = null;

  /**
   *
   * @var array
   */
  protected $concepts   = array();

  /**
   * should ne code generated from this entity, or is it only for meta informations
   * @var boolean
   */
  public $isMeta          = false;

  /**
   * @var LibGenfTreeNodeEntityHead
   */
  public $head            = null;

  /**
   * @var LibGenfTreeNodeEntityUi
   */
  public $ui              = null;

  /**
   * @var LibGenfTreeNodeEventList
   */
  public $events          = null;

  /**
   * @var string
   */
  public $relevance       = 'd-1';
  
////////////////////////////////////////////////////////////////////////////////
// initialize and parse the entity data
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $params
   */
  protected function prepareNode( $params = array() )
  {

    $this->importCategories( );

  }//end protected function prepareNode */

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {
    
    if( isset( $this->node['relevance'] ) )
    {
      $this->relevance = trim($this->node['relevance']);
    }
    

    $classAttr    = $this->builder->getNodelistClass('Attribute');
    $this->childs = new $classAttr( $this->node->attributes, array('entity'=>$this)  );

    $classRef         = $this->builder->getNodelistClass('Reference');
    $this->references = new $classRef( $this->node , array('name'=>trim($this->node['name']))  );

    if( isset( $this->node->ui ) )
    {
      $classUi  = $this->builder->getNodeClass('EntityUi');
      $this->ui = new $classUi( $this->node->ui, array('entity'=>$this) ) ;
    }

    if( isset( $this->node->head ) )
    {
      $classHead  = $this->builder->getNodeClass('EntityHead');
      $this->head = new $classHead( $this->node->head, array('entity'=>$this) ) ;
    }

    if( isset($this->node->events ) )
    {
      $uiClassName              = $this->builder->getNodeClass('EventList');
      $this->events             = new $uiClassName( $this->node->events );
    }

    // only exists if subnode exists
    if( isset($this->node->concepts->concept) )
    {
      foreach( $this->node->concepts->concept as $concept )
      {
        $key = trim($concept['name']);

        $globalConcept = $this->builder->globalConcept(strtolower($key));

        // check if a concept is disabled
        if( false === $globalConcept )
          continue;

        $className = $this->builder->getNodeClass('Concept'.SParserString::subToCamelCase($key));
        if( Webfrap::loadable($className) )
        {
          $this->concepts[strtolower($key)] = new $className( $concept );
        }
        else
        {
          $this->concepts[strtolower($key)] = true;
        }
      }
    }

  }//end protected function loadChilds */


  /**
   *
   */
  protected function importCategories( )
  {

    $categoryClass = $this->builder->getNodeClass( 'Category' );

    $this->categories = new TArray();

    if( isset( $this->node->attributes->categories) )
    {

      foreach( $this->node->attributes->categories->category as $category )
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

    // check that every category from all attributes is in the category list
    foreach( $this->node->attributes->attribute as $attribute )
    {

      $name = (string)$attribute->categories['main'];


      if( !isset($this->categories[$name]) )
        $this->categories[$name] = new $categoryClass( $name );

    }//end foreach

  }//end protected function importCategories */

////////////////////////////////////////////////////////////////////////////////
// modthodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return boolean
   */
  public function webservice()
  {

    foreach( $this->childs as $attribute )
      if( $attribute->autocomplete() )
        return true;

    return false;

  }//end public function webservice */

  /**
   * @param array $params
   * @return LibGenfTreenodelistAttribute
   */
  public function getAttributes( $params = array()  )
  {
    return $this->childs;
  }//end public function getAttributes */


  /**
   * @return LibGenfTreeNodeEntityHead
   */
  public function getHead()
  {
    return $this->head;
  }//end public function getHead */

  /**
   * @return LibGenfTreeNodeEntityUi
   */
  public function getUi()
  {
    return $this->ui;
  }//end public function getUi */

  /**
   * @param string $key
   * @return LibGenfTreeNode
   * @deprecated use getConcept
   */
  public function concept( $key )
  {
    return $this->getConcept($key);
  }//end public function concept
  
  /**
   * @param string $key
   * @return LibGenfTreeNode
   */
  public function getConcept( $key )
  {
    $key = strtolower($key);

    // if no local concept
    if( !isset($this->concepts[$key]) )
    {
      // check for a global concept
      return $this->builder->globalConcept(strtolower($key));
    }
    else
    {
      return $this->concepts[$key];
    }

  }//end public function getConcept

  /**
   * @param string $key
   * @return boolean
   *
   */
  public function hasConcept( $key )
  {

    $key = strtolower($key);

    // if no local concept
    return isset($this->concepts[$key]);


  }//end public function hasConcept

  /**
   * @param array $params
   * @return array<LibGenfTreenodeAttribute>
   */
  public function getSearchAttributes( $params = array()  )
  {
    return $this->childs->getSearchAttributes( $params );
  }//end public function getSearchAttributes */

  /**
   * @param string $key
   * @return LibGenfTreenodeAttribute
   */
  public function getAttribute( $key  )
  {
    return $this->childs->getAttribute( $key );
  }//end public function getAttribute */
  
  /**
   * @return [LibGenfTreenodeAttribute]
   */
  public function getRequiredAttributes(   )
  {
    
    return $this->childs->getRequiredAttributes(  );
    
  }//end public function getRequiredAttributes */

  /**
   * @param array $inCat
   * @param boolean $exclude
   * @return int
   */
  public function countFields( $inCat = array(), $exclude = false )
  {

    if( !$inCat )
      return count( $this->childs );

    $counter = 0;

    if( $exclude  )
    {
      foreach( $this->childs as $attribute )
      {
        if( !$attribute->inCategory($inCat) )
          ++$counter;
      }
    }
    else
    {
      foreach( $this->childs as $attribute )
      {
        if( $attribute->inCategory($inCat) )
          ++$counter;
      }
    }
    return $counter;

  }//end public function countFields */

  /**
   * @param boolean $asString
   * @return array
   *
   */
  public function getAttrList( $asString = false )
  {

    return $this->childs->getAttrList( $asString );

  }//end public function getAttrList */

  /**
   * @param string $key
   * @param boolean|string $asName
   * @return LibGenfNameEntity
   *
   */
  public function getAttrTarget( $key, $asName = true )
  {

    if( '' == trim($key) )
    {
      $this->builder->error
      (
        'Missing the key for requesting the target of an attribute : '
          .' in entity: '.$this->name->name.' '.$this->builder->dumpEnv()
      );
      return null;
    }

    if( !$refAttr = $this->childs->getAttribute( $key ) )
    {
      $this->builder->error
      (
        'Requested the target from an non existing Attribute : '.$key
          .' in entity: '.$this->name->name.' '.$this->builder->dumpEnv()
      );
      return null;
    }

    if( !$target = $refAttr->target() )
    {
      $this->builder->error
      (
        'Requested the target from an non reference Attribute : '.$key
          .' in entity: '.$this->name->name.' '.$this->builder->dumpEnv()
      );
      return null;
    }

    if( $asName )
    {

      if( 'entity' === $asName )
      {
        // references always reference to the management, not to the entity direct
        return $this->builder->getManagement( $target )->getEntity();
      }
      else if( 'management' === $asName )
      {
        return $this->builder->getManagement( $target );
      }
      else
      {
        // references always reference to the management, not to the entity direct
        return $this->builder->getManagement( $target )->getName( );
      }
      
    }
    else
    {
      return $target;
    }

  }//end public function getAttrTarget */

  /**
   * @param string $targetKey
   * @param boolean $asName
   * @return LibGenfTreenodeAttribute
   *
   */
  public function getAttrByTarget( $targetKey , $asName = true )
  {

    foreach( $this->childs as $refAttr  )
    {
      if( $refAttr->refName( $targetKey) )
        return $refAttr;
    }

    return null;

  }//end public function getAttrByTarget */


  /**
   * @return boolean
   */
  public function hasReferences( )
  {
    return $this->references->hasReferences();
  }//end public function hasReferences */

  /**
   * @return LibGenfTreenodelistReference
   */
  public function getReferences( )
  {
    return $this->references->getReferences( );
  }//end public function getReferences */

  /**
   * @return array
   */
  public function getMultiRefs( )
  {
    return $this->references->getMultiRefs();
  }//end public function getReferences */

  /**
   * @return LibGenfTreenodelistReference
   */
  public function getSingleRefs( )
  {
    return $this->references->getSingleRefs();
  }//end public function getReferences */


  /**
   * @return array
   */
  public function cloneMultiRefs( $management = null )
  {
    return $this->references->cloneMultiRefs( $management );

  }//end public function cloneMultiRefs */

  /**
   * @return array
   */
  public function cloneSingleRefs( $management = null )
  {
    return $this->references->cloneSingleRefs( $management );

  }//end public function cloneSingleRefs */

  /**
   * @param string $key
   * @return LibGenfTreenodeReference
   *
   */
  public function getReference( $key  )
  {
    return $this->references->getReference($key);
  }//end public function getReference */

  /**
   * @return LibGenfTreenodelistReference
   */
  public function getReferencesList( )
  {
    return $this->references;
  }//end public function getReferencesList */

  /**
   *
   * @return array
   */
  public function getCategories( )
  {
    return $this->categories;
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
   * check if this entity has a specific category
   * @param string $catName
   * @return LibGenfTreeNodeCategoryManagement
   */
  public function getCategory( $catName )
  {
    return isset( $this->categories[$catName] )
      ? $this->categories[$catName]
      : null;

  }//end public function getCategory */
  
  /**
   * check if this entity has a specific category
   * @return string
   */
  public function getMainCategory( )
  {
    return isset( $this->node->categories['main'] )
      ? $this->node->categories['main']
      : 'default';

  }//end public function getMainCategory */

  /**
   * check if a specific entity is readonly in a given context
   *
   */
  public function readOnlyInContext( $catName )
  {
    return false;
  }//end public function readOnlyInContext

  /**
   *
   * @return string the name of the entity
   */
  public function name()
  {
    return trim( $this->node['name'] );
  }//end public function name */

  /**
   * @return boolean
   * @deprecated no need to check that, cause wgt handles this automatically meanwhile
   */
  public function upload()
  {
    return false;
  }//end public function upload */

  /**
   *
   * @return SimpleXMLElement
   */
  public function getData()
  {

    return isset($this->node->data)
      ? $this->node->data
      : null;

  }//end public function getData */

  /**
   * @return array oder null wenn keine Prozesse angehängt wurden
   */
  public function getProcesses()
  {

    if( !isset( $this->node->processes ) )
      return null;

    $className  = $this->builder->getNodeClass( 'ManagementProcess' );
    $processes  = array();

    foreach( $this->node->processes->process as $process )
    {
      //$this->builder->warn("GOT PROCESS ".trim($process['name']));
      $processes[trim($process['name'])] = new $className( $process );
    }

    return $processes;

  }//end public function getProcesses */

  /**
   * @return array oder null wenn keine Prozesse angehängt wurden
   */
  public function getProcessNameByAttribute( $attrKey )
  {

    if( !isset( $this->node->processes ) )
      return null;

    $processes = $this->node->processes->xpath( './process[@attribute="'.$attrKey.'"]' );

    if(!$processes)
      return null;

    return trim($processes[0]['name']);

  }//end public function getProcesses */

  /**
   * @return LibGenfTreeNodeAccessLevel
   */
  public function getAccessLevel()
  {

    $node = null;

    if( isset($this->node->access->levels) )
      $node = $this->node->access->levels;

    $className            = $this->builder->getNodeClass('AccessLevel');

    return new $className($node);

  }//end public function getAccessLevel */



  /**
   * @return string
   */
  public function debugData()
  {

    $code = 'Entity: '.$this->name->name;
    $code .= " Attributes: ".$this->childs->getAttrList(true);

    return $code;

  }//end public function debugData */

}//end class LibGenfTreeNodeEntity

