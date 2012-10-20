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
class LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array[TContextAttribute]
   */
  public $fields           = array();

  /**
   * @var array[TContextAttribute]
   */
  public $saveFields       = array();
  
  /**
   *
   * @var array
   */
  public $usedFields       = array();

  /**
   * @var array
   */
  public $categoryFields   = array();

  /**
   * @var array
   */
  public $searchFields      = array();

  /**
   * @var array
   */
  public $usedSearchFields  = array();

  /**
   * @var array
   */
  public $freeSearchFields  = array();

  /**
   * @var array
   * @deprecated
   */
  public $categories        = array();

  /**
   * Liste aller möglichen Joins
   * @var TTabJoin
   */
  public $tables            = null;

  /**
   * Index der tatsächlich zu verwendenten Joins für die Query
   * @var array
   */
  public $srcIndex          = array();
  
  /**
   * @var array
   */
  public $searchIndex       = array();
  

  /**
   * Der aktuell gesetzte Kontext
   * @var string
   */
  public $context           = null;


  /**
   * Flag ob im aktuellen Kontext das Listenelement gefiltert wird
   * @var boolean
   */
  public $filtered          = false;

  /**
   * @var string
   */
  public $filterCode    = null;
  
  /**
   * Liste der Filter für den aktuellen Kontext
   * @var [LibGenfTreeNodeFilterCheck]
   */
  public $filters       = null;

  /**
   *
   * @var LibGenfNameManagement
   */
  public $name              = null;

  /**
   * Der Management Knoten des Environments
   * @var LibGenfTreeNodeManagement
   */
  public $management        = null;

  /**
   * Der im Context akutelle BDL Layout Node
   * zb list/table/layout, form/edit/layout
   * @var SimpleXmlElement
   */
  public $layout             = null;

  /**
   *
   * @var LibGenfTreeNodeEntity
   */
  public $entity             = null;

  /**
   *
   * @var LibFormBuilder
   */
  public $formbuilder   = null;

  /**
   *
   * @var LibGenfBuild
   */
  public $builder       = null;

  /**
   *
   * @var TArray
   */
  public $params        = null;

  /**
   *
   * Enter description here ...
   * @var boolean
   */
  public $noSource      = false;

  /**
   *
   * Enter description here ...
   * @var string
   */
  public $type      = 'env';
  

  /**
   * Flag ob ein Editbutton in Listenelementen erstellt werden soll
   * @var boolean
   */
  public $editAble   = true;
  
  /**
   * Flag ob ein Rights Button in Listenelementen erstellt werden soll
   * @var boolean
   */
  public $hasRights  = true;

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return void
   */
  public function cleanContext()
  {

    $this->filtered     = false;
    $this->filterCode   = null;
    
    $this->filters      = array();

    $this->context      = null;
    $this->categories   = null;

    $this->fields       = array();
    $this->tables       = array();
    $this->srcIndex     = array();
    $this->searchIndex  = array();

    $this->searchFields     = array();
    $this->freeSearchFields = array();

    $this->layout     = null;
    $this->params     = null;

  }//end public function cleanContext */

////////////////////////////////////////////////////////////////////////////////
//  magic
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param LibGenfBuild $builder
   * @param SimpleXmlElement $node
   */
  public function __construct( $builder, $node )
  {
    $this->builder = $builder;
  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->debugData();
  }//end public function __toString */


////////////////////////////////////////////////////////////////////////////////
//  getter + setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfName
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */
  
  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement()
  {
    return $this->management;
  }//end public function getManagement */
  
  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getMgmt()
  {
    return $this->management;
  }//end public function getMgmt */
  
  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getContextMgmt()
  {
    
    return $this->management;
    
  }//end public function getContextMgmt 
  
  /**
   *
   * @param string $type
   * @return LibGenfParser
   */
  public function getParser( $type )
  {
    return $this->builder->getParser( $type );
  }//end public function getParser */

  /**
   *
   * @param string $type
   * @param LibGenfEnvManagement $env
   * @return LibGenfParser
   */
  public function getGenerator( $type, $env = null )
  {
    return $this->builder->getGenerator( $type, $env );
  }//end public function getGenerator */

  /**
   *
   * @return LibBdlFilterParser
   */
  public function getFilterParser( )
  {
    return $this->builder->bdlRegistry->getFilterParser( );
  }//end public function getFilterParser */

  /**
   *
   * @return LibBdlFilterParser
   */
  public function getFilterCompiler( )
  {
    return $this->builder->bdlRegistry->getFilterParser( );
  }//end public function getFilterCompiler */

  /**
   *
   * @return LibBdlAclCompiler
   */
  public function getAclCompiler( )
  {
    return $this->builder->bdlRegistry->getAclCompiler( );
  }//end public function getAclCompiler */


  /**
   * @param string $catName
   * @param boolean $filtered
   *
   * @return array
   */
  public function getCategoryFields( $catName , $filtered = true )
  {

    if( $filtered )
    {
      if(!isset( $this->categoryFields[$catName] ))
        return null;

      $cats = $this->categoryFields[$catName];

      $tmpCats = array();

      foreach( $cats as $fieldKey => $field )
      {
        if( isset( $this->usedFields[$fieldKey] ) )
          continue;

        $tmpCats[$fieldKey] = $field;

      }

      return $tmpCats;

    }
    else
    {
      return isset( $this->categoryFields[$catName] )
        ?$this->categoryFields[$catName]
        :null;
    }

  }//end public function getCategoryFields */

  /**
   * @param string $catName
   * @return array
   */
  public function getCategory( $catName )
  {
    return isset( $this->categories[$catName] )
      ?$this->categories[$catName]
      :null;

  }//end public function getCategory */

  /**
   * @param string $key ( format: management:name-attribute:name ) 
   * @param boolean $use
   * 
   * @return TContextAttribute
   */
  public function getField( $key , $use = true )
  {

    if( $use )
    {
      $this->usedFields[$key] = true;
    }

    return isset( $this->fields[$key] )
      ? $this->fields[$key]
      : null;


  }//end public function getField */

  /**
   * @param string $key ( format: management:name-attribute:name )  key für das field
   * @param boolean $checkUsed prüfen ob noch vorhanden
   * 
   * @return boolean
   */
  public function hasField( $key, $checkUsed = false )
  {

    if( $checkUsed )
    {
      // wenn schon benutzt haben wir es nichtmehr zur verfügung
      if( isset($this->usedFields[$key]) )
        return false;
    }

    return isset( $this->fields[$key] )?true:false;


  }//end public function hasField */

  /**
   * @param boolean $filtered
   * @return array
   */
  public function getFields( $filtered = true )
  {

    if( $filtered )
    {

      $tmpFields = array();

      foreach( $this->fields as $fieldKey => $field )
      {
        if( isset( $this->usedFields[$fieldKey] ) )
          continue;

        $tmpFields[$fieldKey] = $field;

      }

      return $tmpFields;

    }
    else
    {
      return $this->fields;
    }

  }//end public function getFields */

  /**
   * @param string $key ( format: management:name-attribute:name ) 
   * 
   * @return TContextAttribute
   */
  public function getSearchField( $key )
  {
    
    return isset( $this->searchFields[$key] )
      ? $this->searchFields[$key]
      : null;
      
  }//end public function getSearchField */

  /**
   * @param boolean $filtered
   * 
   * @return array
   */
  public function getSearchFields( $filtered = true )
  {

    if( $filtered )
    {

      $tmpFields = array();

      foreach( $this->searchFields as $fieldKey => $field )
      {
        if( isset( $this->usedSearchFields[$fieldKey] ) )
          continue;

        $tmpFields[$fieldKey] = $field;

      }

      return $tmpFields;

    }
    else
    {
      return $this->searchFields;
    }

  }//end public function getSearchFields */

  /**
   * @param string $key  ( format: management:name-attribute:name ) 
   */
  public function useField( $key )
  {
    
    $this->usedFields[$key] = true;
    
  }//end public function useField */

  /**
   * @param string $key ( format: management:name-attribute:name ) 
   */
  public function useSearchField( $key )
  {
    
    $this->usedSearchFields[$key] = true;
    
  }//end public function useSearchField */

  /**
   * @param string $context
   * @param string $method
   * @param string $on
   * 
   * @return array<LibGenfTreeNodeEvent>
   */
  public function getEvents( $class, $method, $on )
  {
    
    return $this->management->getEvents( $class, $method, $on );
    
  }//end public function getEvents */
  
  /**
   * @return string
   */
  public function debugData()
  {
    
    return 'class '.get_class($this).' '.$this->name->name.' context: '.$this->context;
    
  }//end public function debugData */

}//end class LibGenfEnv
