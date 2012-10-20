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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiForm
  extends LibGenfTreeNode
{

  /**
   * @var string
   */
  public $context = null;

  /**
   * @var array
   */
  public $tabs  = null;

  /**
   * @var LibGenfTreeNodeUiForm
   */
  public $fallback  = null;

  /**
   * @var LibGenfTreeNodeUi
   */
  public $parent  = null;

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfName $name
   * @param $params
   */
  public function __construct( $node, $context = null, $params = array() )
  {

    $this->builder  = LibGenfBuild::getInstance();

    $this->context  = $context;

    $this->validate( $node );
    $this->prepareNode( $params );
    $this->loadChilds( );

  }//end public function __construct */

/*//////////////////////////////////////////////////////////////////////////////
// Filter
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return string
   */
  public function getFilter(  )
  {

    $filter = null;

    if( isset($this->node->{$this->context}->filter->check ) )
    {
      $filter = $this->node->{$this->context}->filter;
    }
    else if( isset($this->node->filter->check ) )
    {
      $filter = $this->node->filter;
    }

    if( !$filter )
    {
      if( $this->fallback )
      {
        return $this->fallback->getFilter( );
      }
    }

    return $filter;

  }//end public function getFilter */

  /**
   * @return boolean
   */
  public function hasFilter(  )
  {

    if( isset( $this->node->{$this->context}->filter->check ) )
    {
      true;
    }
    else if( isset( $this->node->filter->check  ) )
    {
      true;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->hasFilter( );
      }

      return false;
    }

  }//end public function hasFilter */


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Abfragen ob ein bestimmter Context überhaupt definiert ist
   * @return boolean
   */
  public function hasContext( $context )
  {

    $layout = null;

    if( isset( $this->node->{$context} ) )
    {
      return true;
    }

    if( !$layout )
    {
      if( $this->fallback )
      {
        return $this->fallback->hasContext( $context );
      }
    }

    return false;

  }//end public function hasContext */

  /**
   * @return SimpleXMLElement
   */
  public function getLayout(  )
  {

    $layout = null;

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $layout = $this->node->{$this->context}->layout;
    }
    elseif( isset( $this->node->crud->layout ) )
    {
      $layout = $this->node->crud->layout;
    }

    if( !$layout )
    {
      if( $this->fallback )
      {
        return $this->fallback->getLayout();
      }
    }

    return $layout;

  }//end public function getLayout */

  /**
   * @return array
   */
  public function getDyntextKeys(  )
  {

    $layout = null;
    $keys   = array();

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $layout = $this->node->{$this->context}->layout;
    }
    elseif( isset( $this->node->crud->layout ) )
    {
      $layout = $this->node->crud->layout;
    }

    if( !$layout )
    {
      if( $this->fallback )
      {
        return $this->fallback->getDyntextKeys();
      }
      else
      {
        return $keys;
      }
    }

    $dynTexts = $layout->xpath('.//dyntext');

    foreach( $dynTexts as $dynText )
    {
      $keys[] = trim($dynText['key']);
    }

    return $keys;

  }//end public function getDyntextKeys */


  /**
   * @return array
   */
  public function getReadonlyFields(  )
  {

    $layout = null;

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $layout = $this->node->{$this->context}->layout;
    }
    elseif( isset( $this->node->crud->layout ) )
    {
      $layout = $this->node->crud->layout;
    }

    if( !$layout )
    {
      if( $this->fallback )
      {
        return $this->fallback->getReadonlyFields();
      }
      else
      {
        return array();
      }
    }

    $inputs = $layout->xpath( ".//field[@readonly='true']" );

    return $inputs;

  }//end public function getReadonlyFields */


  /**
   *
   */
  public function getCmsTextKeys(  )
  {

    $layout = null;
    $keys   = array();

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $layout = $this->node->{$this->context}->layout;
    }
    elseif( isset( $this->node->crud->layout ) )
    {
      $layout = $this->node->crud->layout;
    }

    if( !$layout )
    {
      if( $this->fallback )
      {
        return $this->fallback->getCmsTextKeys();
      }
      else
      {
        return array();
      }
    }

    $dynTexts = $layout->xpath( './/cms_text' );

    foreach( $dynTexts as $dynText )
    {
      $keys[] = trim($dynText['key']);
    }

    return $keys;

  }//end public function getCmsTextKeys */

/*//////////////////////////////////////////////////////////////////////////////
// Tabs
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * request the tabs for a form element
   *
   * @return array
   */
  public function getTabs( )
  {

    if( !$this->tabs )
      $this->loadTabs( );

    return $this->tabs;

  }//end public function getTabs */

  /**
   * @param string $key
   * @return LibGenfTreeNodeUiTab
   */
  public function getTab( $key )
  {
    if( is_null( $this->tabs ) )
      $this->loadTabs( );

    return isset( $this->tabs[$key] )
      ? $this->tabs[$key]
      : null;

  }//end public function getTab */
  
  /**
   * @return [LibGenfTreeNodeUiTab]
   */
  public function getDefaultTabs(  )
  {
    if( is_null( $this->tabs ) )
      $this->loadTabs( );
      
    $tabs = array();
    
    foreach( $this->tabs as /* @var $tab LibGenfTreeNodeUiTab */ $tab )
    {
      if( !$tab->loadable() )
        $tabs[] = $tab;
    }

    return $tabs;

  }//end public function getDefaultTabs */

  /**
   * @return array<LibGenfTreeNodeItem>
   */
  public function getAllItems( )
  {

    $layouts = $this->node->xpath( './/layout' );

    if( !$layouts )
    {

      if( $this->fallback )
      {
        return $this->fallback->getAllItems( );
      }

      return null;
    }

    $items = $this->node->xpath( './/item' );

    if( !$items )
      return null;

    $itemObjects = array();

    foreach( $items as $item )
    {
      $className = $this->builder->getNodeClass( 'Item'.SParserString::subToCamelCase( trim( $item['type'] ) ) );

      if( !$className )
      {
        $this->error( "Added nonexisting Item Type: ".trim($item['type']) );
      }

      $itemObjects[trim($item['name'])] = new $className( $item );
    }

    return $itemObjects;

  }//end public function getAllItems */

  /**
   * @return array<LibGenfTreeNodeItem>
   */
  public function getFirstTabItems( )
  {

    if( isset( $this->node->{$this->context}->layout->tabs->body ) )
    {
      $tNode = $this->node->{$this->context}->layout->tabs->body;
    }
    elseif( isset( $this->node->crud->layout->tabs->body ) )
    {
      $tNode = $this->node->crud->layout->tabs->body;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->getFirstTabItems();
      }

      return null;
    }

    $itemObjects = array();

    $tabs = $tNode->xpath( './tab' );

    if( !$tabs )
    {
   
      $items = $tNode->xpath( './/item' );

      if( !$items )
      {
        return null;
      }

      foreach( $items as $item )
      {
        $className = $this->builder->getNodeClass( 'Item'.SParserString::subToCamelCase( trim( $item['type'] ) ) );

        if( !$className )
        {
          $this->error( "Added nonexisting Item Type: ".trim($item['type']) );
        }

        $itemObjects[trim($item['name'])] = new $className( $item );
      }

      return $itemObjects;
    }

    // nur den ersten tab auslesen
    //$tab = $tabs[0];

    foreach( $tabs as $tab )
    {

      // nur tabs die nicht nachgeladen werden
      if( isset( $tab['type'] ) && 'load' == trim($tab['type']) )
        continue;


      $items = $tab->xpath( './/item' );
  
      if( !$items )
      {
        continue;
      }
  
      foreach( $items as $item )
      {

        $className = $this->builder->getNodeClass( 'Item'.SParserString::subToCamelCase( trim( $item['type'] ) ) );
  
        if( !$className )
        {
          $this->builder->dumpError( "Added nonexisting Item Type: ".trim($item['type']) );
        }
  
        $itemObjects[trim($item['name'])] = new $className( $item );
        $itemObjects[trim($item['name'])]->tabName = new LibGenfNameMin( $tab );
      }
    
    }

    return $itemObjects;

  }//end public function getFirstTabItems */

  /**
   * @return array<LibGenfTreeNodeReference>
   */
  public function getFirstTabReferences( )
  {

    if( isset( $this->node->{$this->context}->layout->tabs->body ) )
    {
      $tNode = $this->node->{$this->context}->layout->tabs->body;
    }
    elseif( isset( $this->node->crud->layout->tabs->body ) )
    {
      $tNode = $this->node->crud->layout->tabs->body;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->getFirstTabReferences();
      }

      return null;
    }

    $refNodes = array();

    $tabs = $tNode->xpath( './/tab' );

    // wenn es keine tabs gibt dann wird der ganze body durchsucht
    if( !$tabs )
    {
      $references = $tNode->xpath( './/reference' );

      if( !$references )
      {
        return null;
      }

      foreach( $references as $ref )
      {

        if( $foundRef = $this->management->getReference( trim( $ref['name'] ) ) )
        {
          $refLayout      = new LibGenfTreeNodeLayoutReference($ref);
          $refLayout->ref = $foundRef;

          $refNodes[] = $refLayout;
        }
        else
        {
          $this->builder->error( 'Missing reference: '.trim($ref['name']).' in tab '.$this->name.' '.$this->builder->dumpEnv() );
        }

      }

      return $refNodes;
    }

    // wenn tabs gefunden werden wird nur im ersten tab gesucht
    //$tab = $tabs[0];
    
    foreach( $tabs as $tab )
    {
      
      // nur tabs die nicht nachgeladen werden
      if( isset( $tab['type'] ) && ( 'load' == trim($tab['type']) || 'sub' == trim($tab['type'])  ) )
        continue;
      
      $references = $tab->xpath( './/reference' );
  
      if( !$references )
      {
        continue;
      }
  
      foreach( $references as $ref )
      {
  
        if( $foundRef = $this->management->getReference( trim( $ref['name'] ) ) )
        {
          $refLayout      = new LibGenfTreeNodeLayoutReference($ref); ;
          $refLayout->ref = $foundRef;
  
          $refNodes[] = $refLayout;
        }
        else
        {
          $this->builder->error( 'Missing reference: '.trim($ref['name']).' in tab '.$this->name.' '.$this->builder->dumpEnv() );
        }
  
      }
    }

    return $refNodes;

  }//end public function getFirstTabReferences */

  /**
   * @return array<LibGenfTreeNodeItem>
   */
  public function getContextItems( )
  {

    if( isset( $this->node->{$this->context}->layout->tabs->body ) )
    {
      $tNode = $this->node->{$this->context}->layout->tabs->body;
    }
    elseif( isset( $this->node->crud->layout->tabs->body ) )
    {
      $tNode = $this->node->crud->layout->tabs->body;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->getContextItems();
      }

      return null;
    }

    $itemObjects = array();

    $tabs = $tNode->xpath( './/tab' );

    if( !$tabs )
    {
      $items = $tNode->xpath( './/item' );

      if( !$items )
      {
        return null;
      }

      foreach( $items as $item )
      {
        $className = $this->builder->getNodeClass( 'Item'.SParserString::subToCamelCase( trim( $item['type'] ) ) );

        if( !$className )
        {
          $this->error( "Added nonexisting Item Type: ".trim($item['type']) );
        }

        $itemObjects[trim($item['name'])] = new $className( $item );
      }

      return $itemObjects;
    }


    foreach( $tabs as $tab )
    {
      $items = $tab->xpath( './/item' );

      if( !$items )
      {
        continue;
      }

      foreach( $items as $item )
      {
        $className = $this->builder->getNodeClass( 'Item'.SParserString::subToCamelCase( trim( $item['type'] ) ) );

        if( !$className )
        {
          $this->error( "Added nonexisting Item Type: ".trim($item['type']) );
        }

        $itemObjects[trim($item['name'])] = new $className( $item );
        $itemObjects[trim($item['name'])]->tabName = new LibGenfNameMin( $tab );
      }
    }



    return $itemObjects;

  }//end public function getContextItems */

  /**
   * @param string $key
   * @return array<LibGenfTreeNodeItem>
   */
  public function getTabItems( $key )
  {

    if( isset( $this->node->{$this->context}->layout->tabs->body ) )
    {
      $tNode = $this->node->{$this->context}->layout->tabs->body;
    }
    elseif( isset( $this->node->crud->layout->tabs->body ) )
    {
      $tNode = $this->node->crud->layout->tabs->body;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->getTabItems( $key );
      }

      return null;
    }

    $tabList = $tNode->xpath( './tab[@name="'.$key.'"]' );

    if( !count($tabList) )
    {
      $this->builder->addError( "Request items for a nonexisting tab: {$key} " );
      return null;
    }

    $tabNode = $tabList[0];

    $items = $tabNode->xpath( './/item' );

    if( !$items )
      return null;

    $itemObjects = array();

    foreach( $items as $item )
    {
      $className = $this->builder->getNodeClass( 'Item'.SParserString::subToCamelCase( trim( $item['type'] ) ) );

      if( !$className )
      {
        $this->error( "Added nonexisting Item Type: ".trim($item['type']) );
      }

      $itemObjects[trim($item['name'])] = new $className( $item );
      $itemObjects[trim($item['name'])]->tabName = new LibGenfNameMin( $tabNode );
    }

    return $itemObjects;

  }//end public function getTabItems */
  
  /**
   * @param string $key
   * @return array<LibGenfTreeNodeItem>
   */
  public function getTabDyntextKeys( $key )
  {

    if( isset( $this->node->{$this->context}->layout->tabs->body ) )
    {
      $tNode = $this->node->{$this->context}->layout->tabs->body;
    }
    elseif( isset( $this->node->crud->layout->tabs->body ) )
    {
      $tNode = $this->node->crud->layout->tabs->body;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->getTabDyntextKeys( $key );
      }

      return null;
    }

    $tabList = $tNode->xpath( './tab[@name="'.$key.'"]' );

    if( !count($tabList) )
    {
      $this->builder->addError( "Request items for a nonexisting tab: {$key} " );
      return null;
    }

    $tabNode = $tabList[0];

    $dyntexts = $tabNode->xpath( './/dyntext' );

    if( !$dyntexts )
      return null;

    $keys = array();

    foreach( $dyntexts as $dynText )
    {
      $keys[trim($dynText['key'])] = trim($dynText['key']);
    }

    return $keys;

  }//end public function getTabDyntextKeys */

  /**
   * request the tabs for a form element
   * @return [LibGenfTreeNodeUiTab]
   */
  public function loadTabs(  )
  {

    if( !is_null( $this->tabs )  )
      return;

    $tNode  = null;

    if( isset( $this->node->{$this->context}->layout->tabs->body ) )
    {
      $tNode = $this->node->{$this->context}->layout->tabs->body;
    }
    elseif( isset( $this->node->crud->layout->tabs->body ) )
    {
      $tNode = $this->node->crud->layout->tabs->body;
    }
    else
    {

      if( $this->fallback )
      {
        $this->tabs = $this->fallback->getTabs();
      }
      else
      {
        $this->tabs = array();
      }

      return null;
    }

    $className = $this->builder->getNodeClass( 'UiTab' );
    foreach( $tNode->tab as $tab )
    {
      
      /* @var $tabObj LibGenfTreeNodeUiTab */
      $tabObj = new $className( $tab );
      $tabObj->management = $this->management;

      $this->tabs[trim($tab['name'])] = $tabObj;
    }

  }//end public function loadFormTabs */

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return array
   */
  public function getFields( )
  {

    $raw = null;

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $raw = $this->extractFormLayoutFields( array(), $this->node->{$this->context}->layout );
      //Debug ::console('form '.$this->context.' layout',$raw);
    }
    elseif( isset( $this->node->crud->layout ) )
    {
      $raw = $this->extractFormLayoutFields( array(), $this->node->crud->layout );
      //Debug ::console('form crud layout',$raw);
    }

    if( is_null( $raw ) )
    {
      if( $this->fallback )
      {
        return $this->fallback->getFields();
      }
      else
      {
        return null;
      }
    }


    $fieldClassName = $this->builder->getNodeClass( 'UiFormField' );
    $fields   = array();

    foreach( $raw as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getFields */

  /**
   * @param string $context
   * @return array
   */
  public function getSaveFields(  )
  {

    $raw = null;

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $raw = $this->extractFormSaveFields(array(), $this->node->{$this->context}->layout );
    }
    elseif( isset( $this->node->crud->layout ) )
    {
      $raw = $this->extractFormSaveFields(array(), $this->node->crud->layout );
    }

    if( is_null( $raw ) )
      return null;

    $fieldClassName = $this->builder->getNodeClass( 'UiFormField' );
    $fields   = array();

    foreach( $raw as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getSaveFields */

  /**
   * @param boolean $autoAppendMeta
   */
  public function getCategories( $autoAppendMeta = false )
  {

    $use = null;

    if( isset($this->node->{$this->context}->use->category) )
    {
      $use = $this->node->{$this->context}->use;
    }
    else if( isset($this->node->crud->use->category) )
    {
      $use = $this->node->crud->use;
    }

    if( !$use )
    {
      if( !$this->fallback )
      {
        return null;
      }
      else
      {
        return $this->fallback->getCategories( $autoAppendMeta );
      }
    }

    $cats = array();

    foreach( $use->category as $category )
    {
      $cats[] = trim($category['name']);
    }

    if( $autoAppendMeta  )
    {
      if( !in_array( 'meta', $cats ) )
        $cats[] = 'meta';
    }

    return $cats;

  }//end public function getCategories */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractFormLayoutFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;
      
    $breaker = array( 'user_form', 'item' );

    $meta = false;

    foreach( $children as $child )
    {

      if( 'field' == $child->getName() )
      {
        $fields[] = $child;
      }
      else if( in_array($child->getName(), $breaker)  )
      {
        // user_form inputs hier nicht auslesen
        continue;
      }
      else if( 'category' == $child->getName() )
      {

        if( 'meta' == trim( $child['name'] ) )
          $meta = true;

        $fields = $this->getFormCategoryFields( $fields, trim( $child['name'] ) );
      }
      else
      {
        $fields = $this->extractFormLayoutFields( $fields, $child );
      }
    }

    if( !$meta )
      $fields = $this->getFormCategoryFields( $fields, 'meta' );

    return $fields;

  }//end public function extractFormLayoutFields */

  /**
   * @param array $fields
   * @param string $catname
   */
  public function getFormCategoryFields( $fields, $catname  )
  {

    $catFields = $this->management->getCategoryFields( $catname );

    foreach( $catFields as $field )
    {
      $fields[] = $field;
    }

    return $fields;

  }//end public function getFormCategoryFields */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractFormSaveFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;

    $meta = false;

    $fieldNames = array( 'field', 'value' );
    $breaker = array( 'user_form', 'item', 'list' );

    foreach( $children as $child )
    {

      if( in_array( $child->getName(), $fieldNames )  )
      {

        if( isset( $child['readonly'] ) )
        {
          continue;
        }

        $fields[] = $child;
      }
      else if( in_array($child->getName(), $breaker)  )
      {
        // user_form inputs hier nicht auslesen
        continue;
      }
      else if( 'category' == $child->getName() )
      {

        if( isset($child['readonly']) )
        {
          continue;
        }

        // prüfen ob die meta kategory angehängt wurde
        // diese wird in einigen fällen zum speichern benötigt
        if( 'meta' == trim( $child['name'] ) )
          $meta = true;

        $fields = $this->getFormCategoryFields( $fields, trim($child['name']) );
      }
      else
      {
        $fields = $this->extractFormSaveFields( $fields, $child );
      }
    }

    // wenn die metakategorie nicht vorhanden ist, wird sie automatisch
    // ans ende des formulars gepackt
    if( !$meta )
      $fields = $this->getFormCategoryFields( $fields, 'meta' );

    return $fields;

  }//end public function extractFormSaveFields */

/*//////////////////////////////////////////////////////////////////////////////
// Mask Actions
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return array<LibGenfTreeNodeUiButtonAction>
   */
  public function getMaskActions()
  {

    $panel = null;

    if( isset( $this->node->{$this->context}->panel ) )
      $panel = $this->node->{$this->context}->panel;

    else if( isset( $this->node->crud->panel ) )
      $panel = $this->node->crud->panel;

    if( !$panel )
    {
      if( $this->fallback )
      {
        return $this->fallback->getMaskActions();
      }
      else
      {
        return null;
      }
    }

    $entries = array();

    foreach( $panel->action as $action )
    {

      $type = SParserString::subToCamelCase( trim($action['type']) );

      $className = 'LibGenfTreeNodeUiButtonAction_'.$type;

      if( !Webfrap::classLoadable($className) )
      {
        $this->builder->dumpError( "Requested nonexisting Button type ".$type );
        continue;
      }


      $entries[] = new $className($action);
    }

    return $entries;

  }//end public function getMaskActions */

////////////////////////////////////////////////////////////////////////////////
// Panel
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return boolean
   */
  public function isPanelClear()
  {

    if( isset( $this->node->{$this->context}->panel['clear'] ) )
    {
      return trim($this->node->{$this->context}->panel['clear']) == 'true'
        ? true
        : false;
    }

    else if( isset( $this->node->crud->panel['clear'] ) )
    {
      return trim($this->node->crud->panel['clear']) == 'true'
        ? true
        : false;
    }

    if( $this->fallback )
    {
      return $this->fallback->isPanelClear();
    }

    return false;

  }//end public function isPanelClear */

////////////////////////////////////////////////////////////////////////////////
// Properties
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   * @param string $attribute
   * @return string
   */
  public function property( $key, $attribute = null )
  {

    if( $attribute )
    {
      return isset( $this->node->{$this->context}->properties->{$key}[$attribute] )
        ?trim( $this->node->{$this->context}->properties->{$key}[$attribute] )
        :$this->fallback
          ?$this->fallback->property($key, $attribute)
          :null;
    }
    else
    {
      return isset( $this->node->{$this->context}->properties->{$key} )
        ?trim( $this->node->{$this->context}->properties->{$key} )
        :$this->fallback
          ?$this->fallback->property($key, $attribute)
          :null;
    }

  }//end public function property */

}//end class LibGenfTreeNodeUiForm

