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
class LibGenfTreeNodeUiListing
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var string
   */
  public $context = null;

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

  /**
   * @var LibGenfTreeNodeUiListing
   */
  public $fallback = null;
  
  /**
   * Referenzen auf dem Listenelement
   * @var array
   */
  public $listReferences = null;

  /**
   * Referenzen auf dem Listenelement
   * @var array
   */
  public $embededRoles = null;
  
  /**
   * @var LibGenfTreeNodeUi
   */
  public $parent = null;

/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @param SimpleXmlElement $node
   * @param string $context
   * @param array $params
   */
  public function __construct( $node , $context = null, $params = array() )
  {

    $this->builder  = LibGenfBuild::getInstance();

    if( $context )
      $this->context  = $context;

    $this->validate( $node );
    $this->prepareNode( $params );
    $this->loadChilds( );

  }//end public function __construct */

  /**
   * @return string
   */
  public function getFooterType()
  {

    $type = null;

    if( isset($this->node->{$this->context}->properties->footer['type']) )
    {
      $type = trim($this->node->{$this->context}->properties->footer['type']);
    }
    elseif (isset( $this->node->properties->footer['type']) )
    {
      $type = trim( $this->node->properties->footer['type'] );
    }
    else if( isset($this->node->{$this->context}->footer['type']) )
    {
      $type = trim($this->node->{$this->context}->footer['type']);
    }
    elseif (isset( $this->node->footer['type']) )
    {
      $type = trim( $this->node->footer['type'] );
    }

    if( !$type && $this->fallback )
    {
      return $this->fallback->getFooterType();
    }

    if( !$type )
      $type = 'default';

    return $type;

  }//end public function getFooterType */

  /**
   * @return boolean
   */
  public function isEditAble()
  {

    // layout can only be on a defined list element but not global for
    // all list elements
    if( !isset($this->node->{$this->context}->layout ) )
    {
      if( $this->fallback )
      {
        return $this->fallback->isEditAble();
      }
      else
      {
        return false;
      }
    }
    else
    {
      $editAble = (boolean)count($this->node->{$this->context}->layout->xpath( './/input' ) );

      return $editAble;
    }

  }//end public function isEditAble */

  /**
   * @return array
   */
  public function hasGroupings(  )
  {

    $context = $this->context;

    if( isset($this->node->$context->groupings->group ) )
    {
      return true;
    }
    elseif( isset($this->node->groupings->group ) )
    {
      return true;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->hasGroupings(  );
      }

      return false;
    }

  }//end public function hasGroupings

  /**
   * @return array<LibGenfTreeNodeUiGroup>
   */
  public function getGroupings(  )
  {

    if( isset($this->node->$this->context->groupings ) )
    {
      $use = $this->node->$this->context->groupings;
    }
    elseif( isset($this->node->groupings) )
    {
      $use = $this->node->groupings;
    }
    else
    {

      if( $this->fallback  )
      {
        return $this->fallback->getGroupings( );
      }
      else
      {
        return array();
      }

    }

    $groups = array();

    $className = $this->builder->getNodeClass( 'UiGroup' );

    foreach( $use->group as $group )
    {
      $groupObj = new $className( $group );
      $groupObj->context = $this->context;
      $groups[] = $groupObj;
    }

    return $groups;

  }//end public function getGroupings */

  /**
   * @param string $context
   */
  public function groupingRequired(  )
  {

    $groupings = $this->getGroupings(  );

    foreach( $groupings as $group )
    {
      if( $group->required() )
        return true;
    }

    return false;

  }//end public function groupingRequired */

  /**
   * @return LibGenfTreeNodeControlPanel
   */
  public function getPanel( )
  {

    $panel = null;

    if ( isset($this->node->{$this->context}->panel) )
    {
      $panel = $this->node->{$this->context}->panel;
    }
    elseif ( isset( $this->node->panel ) )
    {
      $panel = $this->node->panel;
    }

    if( !$panel )
    {
      //Debug::console( "found no panel in ".$this );

      // prüfen ob es ein fallback panel gibt
      if( $this->fallback )
      {
        return $this->fallback->getPanel( );
      }

      return null;
    }

    $className  = $this->builder->getNodeClass( 'ControlPanel' );

    return new $className( $panel );

  }//end public function getPanel */

  /**
   * @return [LibGenfTreeNodeUiListAction]
   */
  public function getActions( )
  {

    $actionsNode = null;

    if ( isset($this->node->{$this->context}->actions) )
    {
      $actionsNode = $this->node->{$this->context}->actions;
    }

    if( !$actionsNode )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->getActions( );
      }

      return null;
    }

    $actions = array();

    $className  = $this->builder->getNodeClass( 'UiListAction' );

    foreach( $actionsNode->node as $action )
    {
      $actions[] = new $className( $action );
    }

    return $actions;

  }//end public function getActions */

  /**
   * @return array<LibGenfTreeNodeCondition>
   */
  public function getListConditions( )
  {

    $actionsNode = null;

    if( !isset($this->node->{$this->context}->actions ) )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->getListConditions( );
      }

      return null;
    }

    if( !isset($this->node->{$this->context}->actions->node->conditions->condition) )
    {
      return null;
    }

    $query = './actions/node/conditions/condition';

    $conditions = array();

    $conditionList = $this->node->{$this->context}->xpath( $query );

    foreach( $conditionList as $condition )
    {

      $nodeClass = 'LibGenfTreeNodeCondition'
        .SParserString::subToCamelCase( trim($condition['type']) );

      if( !Webfrap::classLoadable( $nodeClass ) )
      {
        $this->builder->dumpError( "Requested nonexisting Conditiontype {$nodeClass}" );
        continue;
      }

      $conditions[] = new $nodeClass( $condition );
    }

    return $conditions;

  }//end public function getListConditions */


  /**
   * @param string $type
   * @param boolean $withControls Nur Filter zurückgeben welche auch Controll Elemente beschreiben
   * @return array<LibGenfTreeNodeFilterCheck>
   */
  public function getFilter( $type = null, $withControls = null )
  {

    $filter = array();

    if( isset($this->node->{$this->context}->filter->check ) )
    {
      $filter = $this->node->{$this->context}->filter;
    }
    else if( isset($this->node->filter->check ) )
    {
      $filter = $this->node->filter;
    }

    if( !$filter  )
    {

      if( $this->fallback )
      {
        return $this->fallback->getFilter( $type, $withControls );
      }

      return $filter;
    }

    if( $type )
    {

      $type    = strtolower($type);
      $checks  = array();

      foreach( $filter->check as $check  )
      {

        if( $type != strtolower( trim( $check['type'] ) ) )
          continue;

        if( !is_null( $withControls ) )
        {

          if( $withControls == Bdl::FILTER_CONTROL_ABLE )
          {
            if( isset( $check->control_able ) || isset( $check->controls ) )
            {

              $checks[] = new LibGenfTreeNodeFilterCheck( $check );
              continue;
            }
          }
          else if( $withControls == Bdl::FILTER_CONTROL )
          {

            if( isset( $check->controls ) )
              $checks[] = new LibGenfTreeNodeFilterCheck( $check );

          }

        }
        else
        {
          $checks[] = new LibGenfTreeNodeFilterCheck( $check );
        }

      }

      return $checks;

    }
    else // else type
    {

      if( !$filter )
      {
        return array();
      }

      $checks = array();

      foreach( $filter->check as $check  )
      {

        if( !is_null( $withControls ) )
        {

          if( $withControls == Bdl::FILTER_CONTROL_ABLE )
          {
            if( isset( $check->control_able ) || isset( $check->controls ) )
            {
              $checks[] = new LibGenfTreeNodeFilterCheck( $check );
            }
            continue;
          }
          else if( $withControls == Bdl::FILTER_CONTROL )
          {
            if( isset( $check->controls ) )
              $checks[] = new LibGenfTreeNodeFilterCheck( $check );
          }

        }
        else
        {
          $checks[] = new LibGenfTreeNodeFilterCheck( $check );
        }

      }

      return $checks;
    }


  }//end public function getFilter */


  /**
   * Prüfen ob filter vorhanden sind
   *
   * @param string $type
   * @param boolean $withControls Nur Filter zurückgeben welche auch Controll Elemente beschreiben
   * @return string
   */
  public function hasFilter( $type = null, $withControls = null )
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

    if( !$filter  )
    {

      if( $this->fallback )
      {
        return $this->fallback->hasFilter( $type, $withControls );
      }

      return false;
    }

    if( $type )
    {

      $type    = strtolower($type);
      $checks  = array();

      foreach( $filter->check as $check  )
      {

        if( $type != strtolower( trim( $check['type'] ) ) )
          continue;

        if( !is_null( $withControls ) )
        {

          if( $withControls == Bdl::FILTER_CONTROL_ABLE )
          {
            if( isset( $check->control_able ) || isset( $check->controls ) )
            {
              return true;
            }
          }

          if( $withControls == Bdl::FILTER_CONTROL )
          {
            if( isset( $check->controls ) )
              return true;
          }

        }
        else
        {
          return true;
        }

      }

      return false;

    }
    else // else type
    {

      if( !$filter )
      {
        return array();
      }

      $checks = array();

      foreach( $filter->check as $check  )
      {

        if( !is_null( $withControls ) )
        {

          if( $withControls == Bdl::FILTER_CONTROL_ABLE )
          {
            if( isset( $check->control_able ) || isset( $check->controls ) )
            {
              return true;
            }
          }

          if( $withControls == Bdl::FILTER_CONTROL )
          {
            if( isset( $check->controls ) )
              return true;
          }

        }
        else
        {
          return true;
        }

      }

      return false;
    }


  }//end public function hasFilter */

  /**
   * Erfragen der Context filter.
   * Ein Context Filter wird nur eingebunden wenn er expliziet über die
   * URL angefragt wird.
   *
   * Wird zb zum einschränken in selections verwendet, zb nur die Employees
   * anzeigen welche auch eine Verknüpfung zum aktuellen Projekt haben etc.
   *
   * @return array<LibGenfTreeNodeFilterCheck>
   */
  public function getContextFilter( )
  {

    $filter = array();

    if( isset($this->node->{$this->context}->context_filter->check ) )
    {
      $filter = $this->node->{$this->context}->context_filter;
    }
    else if( isset($this->node->context_filter->check ) )
    {
      $filter = $this->node->context_filter;
    }

    if( !$filter  )
    {
      if( $this->fallback )
      {
        return $this->fallback->getContextFilter( );
      }
      else
      {
        return  array();
      }
    }

    $checks = array();

    foreach( $filter->check as $check  )
    {
      $checks[] = new LibGenfTreeNodeFilterCheck( $check );
    }

    return $checks;


  }//end public function getContextFilter */


  /**
   * Prüfen ob context filter vorhanden sind
   *
   * @return boolean
   */
  public function hasContextFilter( )
  {

    $filter = null;

    if( isset($this->node->{$this->context}->context_filter->check ) )
    {
      $filter = $this->node->{$this->context}->context_filter;
    }
    else if( isset($this->node->context_filter->check ) )
    {
      $filter = $this->node->context_filter;
    }

    if( !$filter  )
    {

      if( $this->fallback )
      {
        return $this->fallback->hasContextFilter( );
      }

      return false;
    }

    return true;


  }//end public function hasContextFilter */

  /**
   * @param string $context
   * @return string
   */
  public function getFilterClass( $context = null )
  {

    if( !$context )
      $context = $this->context;

    if( isset( $this->node->{$context}->filter['class'] ) )
    {
      $filter = trim( $this->node->{$context}->filter['class'] );

      if( '' != $filter )
        return SParserString::subToCamelCase( $filter );

    }
    else if( isset( $this->node->filter['class'] ) )
    {
      $filter = trim( $this->node->filter['class'] );

      if( '' != $filter )
        return SParserString::subToCamelCase( $filter );

    }

    if( $this->fallback )
    {
      return $this->fallback->getFilter( $context );
    }

    return null;

  }//end public function getFilterClass */

  /**
   * @return SimpleXMLElement
   */
  public function getLayout(  )
  {


    // layout can only be on a defined list element but not global for
    // all list elements
    if( !isset($this->node->{$this->context}->layout ) )
    {
      if( $this->fallback )
      {
        return $this->fallback->getLayout();
      }
      else
      {
        return null;
      }
    }
    else
    {
      return $this->node->{$this->context}->layout;
    }

  }//end public function getLayout */


  /**
   * @return LibGenfTreeNodeUiListColorSource
   */
  public function getColorSource( )
  {

    $colorSource = null;

    // layout can only be on a defined list element but not global for
    // all list elements
    if( isset( $this->node->{$this->context}->color_source ) )
    {
      $colorSource = $this->node->{$this->context}->color_source;
    }
    else if( isset( $this->node->color_source ) )
    {
      $colorSource = $this->node->color_source;
    }
    else
    {
      if( $this->fallback )
      {
        return $this->fallback->getColorSource();
      }
      else
      {
        return null;
      }
    }

    return new LibGenfTreeNodeUiListColorSource( $colorSource );

  }//end public function getColorSource */

////////////////////////////////////////////////////////////////////////////////
// Fields
////////////////////////////////////////////////////////////////////////////////

  /**
   * Extrahieren der tabellen fields aus dem ui layout
   *
   * @param string $context der listing context
   *
   * @return array<LibGenfTreeNodeUiListField>
   */
  public function getFields(  )
  {

    $context         = $this->context;
    $fieldClassName  = $this->builder->getNodeClass( 'UiListField' );

    if( isset($this->node->$context->layout ) )
    {
      $layout = $this->node->$context->layout;
    }
    else
    {
      $layout = null;
    }

    if( $layout )
    {
      $tmp     = $this->extractLayoutFields( array(), $layout );
      $fields  = array();

      foreach( $tmp as $field )
      {
        $fields[] = new $fieldClassName( $field );
      }

      return $fields;
    }

    if( isset( $this->node->$context->fields->field ) )
    {
      $fieldsNode = $this->node->$context->fields;
    }
    else
    {
      $fieldsNode = null;
    }

    if( !$fieldsNode )
    {

      if( $this->fallback )
      {
        return $this->fallback->getFields();
      }
      return null;

    }

    $fields = array();

    foreach( $fieldsNode->field as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getFields */

  /**
   * Abfragen der Editierbaren Felder,
   * Wird zb benötigt wenn einige der Felder Listenelemente sind um die
   * Datenquellen der Listenelemente erstellen zu können
   *
   * @param array $types Liste der Inputtypen die erfragt werden sollen
   *
   * @return array<LibGenfTreeNodeUiListField>
   */
  public function getEditFields( $types = array() )
  {

    if( !isset( $this->node->{$this->context}->layout ) )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->getEditFields( $types );
      }

      return null;
    }

    $query  = './/input';
    $result = $this->node->{$this->context}->layout->xpath( $query );

    if( !count($result) )
      return null;

    $inputs = array();

    $fieldClassName  = $this->builder->getNodeClass( 'UiListField' );

    foreach( $result as $input )
    {

      //$this->builder->error( Debug::xmlPath($input) );

      if( $types )
      {

        if( !isset( $input->ui_element['type'] ) || '' ==  trim($input->ui_element['type']) )
        {
          continue;
        }

        if( !in_array( strtolower(trim($input->ui_element['type'])), $types  ) )
        {
          continue;
        }
      }

      $inputs[] = new $fieldClassName( $input );
    }

    return $inputs;

  }//end public function getEditFields */

  /**
   * Anfragen aller Sortable Cols
   *
   * @return array<LibGenfTreeNodeUiListOrderCol>
   */
  public function getSortCols( )
  {
    
    $layout = null;
    
    if( isset( $this->node->{$this->context}->layout->thead->row->col ) )
      $layout = $this->node->{$this->context}->layout->thead;
    elseif( isset( $this->node->{$this->context}->layout->row->col ) ) 
      $layout = $this->node->{$this->context}->layout;

    if( !$layout )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->getSortCols( );
      }

      return null;
    }

    $cols            = array();

    $fieldClassName  = $this->builder->getNodeClass( 'UiListOrderCol' );
    $rows            = $layout->row;

    foreach( $rows->col as $col )
    {
      
      if( !isset( $col['order_attr'] ) )
        continue;
      
      $cols[] = new $fieldClassName( $col );
    }

    return $cols;

  }//end public function getSortCols */
  
  /**
   * Anfragen aller Sortable Cols
   *
   * @return array<LibGenfTreeNodeUiListSearchCol>
   */
  public function hasSortCols( )
  {
    
    $layout = null;
    
    if( isset( $this->node->{$this->context}->layout->thead->row->col ) )
      $layout = $this->node->{$this->context}->layout->thead;
    elseif( isset( $this->node->{$this->context}->layout->row->col ) ) 
      $layout = $this->node->{$this->context}->layout;

    if( !$layout )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->hasSortCols( );
      }

      return false;
    }

    foreach( $layout->row->col as $col )
    {
      
      if( !isset( $col['order_attr'] ) )
        continue;
      
      return true;
    }

    return false;

  }//end public function hasSortCols */

  /**
   * Anfragen aller Sortable Cols
   *
   * @return array[LibGenfTreeNodeUiListSearchCol]
   */
  public function getSearchCols( )
  {
    
    $layout = null;
    
    if( isset( $this->node->{$this->context}->layout->thead->row->col ) )
      $layout = $this->node->{$this->context}->layout->thead;
    elseif( isset( $this->node->{$this->context}->layout->row->col ) ) 
      $layout = $this->node->{$this->context}->layout;

    if( !$layout )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->getSearchCols( );
      }

      return false;
    }

    $cols          = array();

    $colClassName  = $this->builder->getNodeClass( 'UiListSearchCol' );
    $rows          = $layout->row;

    foreach( $rows->col as $col )
    {
      
      if( !isset( $col['search_attr'] ) && !isset( $col['search_fields'] ) && !isset( $col['search_roles'] )  )
        continue;
      
      $cols[] = new $colClassName( $col );
    }

    return $cols;

  }//end public function getSearchCols */
  
  /**
   * Anfragen aller Sortable Cols
   *
   * @return boolean
   */
  public function hasSearchCols( )
  {

    $layout = null;
    
    if( isset( $this->node->{$this->context}->layout->thead->row->col ) )
      $layout = $this->node->{$this->context}->layout->thead;
    elseif( isset( $this->node->{$this->context}->layout->row->col ) ) 
      $layout = $this->node->{$this->context}->layout;
    
    if( !$layout )
    {

      // fallback prüfen
      if( $this->fallback )
      {
        return $this->fallback->hasSearchCols( );
      }

      return false;
    }

    foreach( $layout->row->col as $col )
    {
      
      if( !isset( $col['search_attr'] ) && !isset( $col['search_fields'] ) && !isset( $col['search_roles'] ) )
        continue;
      
      return true;
    }

    return false;

  }//end public function getSearchCols */
  
  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractLayoutFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;

    $listElem   = array( 'field', 'value', 'input' );
    $breakElem  = array( 'reference' );

    foreach( $children as $child )
    {

      $nodeName = strtolower( $child->getName() );

      if( in_array( $nodeName, $listElem ) )
      {
        $fields[] = $child;
        
        if( isset($child->action) && isset( $child->action['field'] )  )
        {
          $fields[] = array( 'name' => trim($child->action['field']) );
        }
        
      }
      elseif( in_array( $nodeName, $breakElem ) )
      {
        // don't collect values in this elements
        break;
      }
      else
      {
        $fields = $this->extractLayoutFields( $fields, $child );
      }

    }

    return $fields;

  }//end public function extractLayoutFields */

////////////////////////////////////////////////////////////////////////////////
// search
////////////////////////////////////////////////////////////////////////////////

  /**
   * check if there are given search fields, to be used in this listing view
   * @return array<SimpleXMLElement>
   */
  public function getSearchFields(  )
  {

    //$search = null;
    if( isset( $this->node->{$this->context}->search->field ) )
    {
      return $this->extractSearchFields( $this->node->{$this->context}->search );
    }
    else if( isset( $this->node->search->field ) )
    {
      return $this->extractSearchFields( $this->node->search );
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->getSearchFields(  );
      }

      return null;
    }

    //$fieldClassName = $this->builder->getNodeClass('UiFormField');

  }//end public function getSearchFields */

  /**
   * @return boolean
   */
  public function hasSearchFields()
  {
      //$search = null;
    if( isset( $this->node->{$this->context}->search->field ) )
    {
      return true;
    }
    else if( isset( $this->node->search->field ) )
    {
      return true;
    }
    else
    {

      if( $this->fallback )
      {
        return $this->fallback->hasSearchFields(  );
      }

      return false;
    }

  }//end public function hasSearchFields */


  /**
   * check if there are given search fields, to be used in this listing view
   * @param SimpleXmlElement $search
   * @return array<SimpleXMLElement>
   */
  protected function extractSearchFields( $search )
  {

    $fields = array();

    foreach( $search->field as $field )
    {
      $fields[] = $field;
    }

    return $fields;

  }//end protected function extractSearchFields */

////////////////////////////////////////////////////////////////////////////////
// properties
////////////////////////////////////////////////////////////////////////////////

  /**
   * Eine Property aus dem ui element auslesen
   *
   * @param string $key
   * @param string $attr
   */
  public function getProperty( $key, $attr )
  {

    if( isset($this->node->{$this->context}->properties->{$key}[$attr] ) )
    {
      return trim( $this->node->{$this->context}->properties->{$key}[$attr] );
    }

    if( isset($this->node->listing->properties->{$key}[$attr] ) )
    {
      return trim( $this->node->listing->properties->{$key}[$attr] );
    }

    if( $this->fallback )
    {
      return $this->fallback->getProperty( $key, $attr );
    }

    return null;

  }//end public function getProperty */

/*//////////////////////////////////////////////////////////////////////////////
// Control
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @return array<LibGenfTreeNodeUiListMenuAction>
   */
  public function getListActions()
  {

    $menu = null;

    if( isset( $this->node->{$this->context}->menu ) )
      $menu = $this->node->{$this->context}->menu;

    else if( isset( $this->node->listing->menu ) )
      $menu = $this->node->listing->menu;    
      
    else if( isset( $this->node->menu ) )
      $menu = $this->node->menu;

    if( !$menu )
    {
      if( $this->fallback )
      {
        return $this->fallback->getListActions();
      }
      else
      {
        return null;
      }
    }

    $entries = array();

    foreach( $menu->action as $action )
    {
      $entries[] = new LibGenfTreeNodeUiListMenuAction($action);
    }

    return $entries;

  }//end public function getListActions */

  /**
   * @return array<LibGenfTreeNodeUiListMenuAction>
   */
  public function getListActionCoditions()
  {

    $menu = null;

    if( isset( $this->node->{$this->context}->menu ) )
      $menu = $this->node->{$this->context}->menu;

    else if( isset( $this->node->listing->menu ) )
      $menu = $this->node->listing->menu;

    else if( isset( $this->node->menu ) )
      $menu = $this->node->menu;

    if( !$menu )
    {
      if( $this->fallback )
      {
        return $this->fallback->getListActions();
      }
      else
      {
        return null;
      }
    }

    $entries = array();

    foreach( $menu->action as $action )
    {
      $entries[] = new LibGenfTreeNodeUiListMenuAction($action);
    }

    return $entries;

  }//end public function getListActionCoditions */

  /**
   * @return LibGenfTreeNodeUiControls
   */
  public function getControls()
  {

    $controls = null;

    if( isset( $this->node->{$this->context}->controls ) )
      $controls = $this->node->{$this->context}->controls;

    else if( isset( $this->node->controls ) )
      $controls = $this->node->controls;

    if( !$controls )
    {
      if( $this->fallback )
      {
        return $this->fallback->getControls();
      }
      else
      {
        return null;
      }
    }

    return new LibGenfTreeNodeUiControls( $controls );

  }//end public function getControls */

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

    else if( isset( $this->node->listing->panel ) )
      $panel = $this->node->listing->panel;

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


  /**
   * @return boolean
   */
  public function isPanelClear()
  {

    if( isset( $this->node->{$this->context}->panel['clear'] ) )
    {
      return trim($this->node->{$this->context}->panel['clear']) === 'true'
        ? true
        : false;
    }

    else if( isset( $this->node->listing->panel['clear'] ) )
    {
      return trim($this->node->listing->panel['clear']) === 'true'
        ? true
        : false;
    }

    else if( isset( $this->node->panel['clear'] ) )
    {
      return trim($this->node->panel['clear']) === 'true'
        ? true
        : false;
    }

    if( $this->fallback )
    {
      return $this->fallback->isPanelClear();
    }

    return false;

  }//end public function isPanelClear */

/*//////////////////////////////////////////////////////////////////////////////
// Order
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return array<LibGenfTreeNodeFieldOrder>
   *
   */
  public function getOrder(  )
  {

    $order = array();

    if( isset($this->node->{$this->context}->order_by->field ) )
    {
      foreach( $this->node->{$this->context}->order_by->field as $field )
      {
        $order[] = new LibGenfTreeNodeFieldOrder( $field );
      }
      return $order;
    }

    if( isset($this->node->order_by->field ) )
    {
      foreach( $this->node->order_by->field as $field )
      {
        $order[] = new LibGenfTreeNodeFieldOrder( $field );
      }
      return $order;
    }

    if( $this->fallback )
    {
      return $this->fallback->getOrder();
    }

    return null;

  }//end public function getOrder */

////////////////////////////////////////////////////////////////////////////////
// Debug
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getListingReferences( $env )
  {

    // checken ob es für den context auf dem Management Node eine layout beschreibung gibt
    if( isset($this->node->{$this->context}->layout ) )
    {
      $tmp = $this->extractListReferences( array(), $this->node->{$this->context}->layout );

      $fieldClassName = $this->builder->getNodeClass( 'UiListReference' );
      $references     = array();

      foreach( $tmp as $ref )
      {
        
        /* @var $lRef LibGenfTreeNodeUiListReference   */
        $lRef         = new $fieldClassName( $ref );
        $dbgTarget = '';

        if( isset($env->ref) )
        {

          if( 'target' == $lRef->refType || $env->ref->relation(Bdl::MANY_TO_ONE)  )
          {
            $lRef->ref    = $env->ref->targetManagement()->getReference( trim( $ref['target'] ), $ref );
            $dbgTarget = ' target ref: '.$env->ref->targetManagement()->name->name;
          }
          else
          {
            $lRef->ref    = $env->ref->connectionManagement()->getReference( trim( $ref['target'] ), $ref );
            $dbgTarget = ' con ref: '.$env->ref->connectionManagement()->name->name;
          }

        }
        else
        {
          $lRef->ref    = $env->management->getReference( trim( $ref['target'] ), $ref );
        }
        
        //Debug::console( 'GOT LIST REF '.$lRef->name->name.' in this '.$env->name->name.' context: '.$this->context.' '.$dbgTarget );

        $references[$lRef->name->name] = $lRef;

      }

      $this->listReferences = $references;
      return $references;

    }

    if( !isset( $this->node->{$this->context}->fields->field ) )
    {
      
      if( $this->fallback )
      {
        $this->listReferences = $this->fallback->getListingReferences( $env );
        return $this->listReferences;
      }
      else 
      {
        return null;
      }
      
    }
    

    $references     = array();
    $use            = $this->node->{$this->context}->fields;
    $fieldClassName = $this->builder->getNodeClass('UiListReference');

    foreach( $use->field as $field )
    {

      if( isset($field['target']) )
      {
        $lRef       = new $fieldClassName( $field );
        $lRef->ref  = $env->management->getReference( trim( $field['target'] ), $field );

        $references[$lRef->name->name] = $lRef;
      }

    }

    $this->listReferences = $references;
    return $references;

  }//end public function getListingReferences */

  /**
   * check if a listing element has listing reference elements
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function hasListingReferences( $env, $context )
  {

    if( isset($this->node->{$this->context}->layout ) )
    {
      $tmp = $this->extractListReferences( array(), $this->node->{$this->context}->layout );
      return (boolean)count($tmp);
    }

    if( !isset($this->node->{$this->context}->fields->field ) )
    {
      if( $this->fallback )
      {
        return $this->fallback->hasListingReferences( $env, $context );
      }
      else 
      {
        return false;
      }
    }
     

    $references     = array();
    $use            = $this->node->{$this->context}->fields;

    foreach( $use->field as $field )
    {

      if( isset($field['target']) )
      {
        return true;
      }

    }

    return false;

  }//end public function hasListingReferences */

  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getListingReference( $env, $key )
  {

    if( !$this->listReferences )
      $this->getListingReferences( $env );

    return isset( $this->listReferences[$key] )
      ?$this->listReferences[$key]
      :null;

  }//end public function getListingReference */
  
  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractListLayoutFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;

    $listElem   = array('field','value','input');
    $breakElem  = array('reference','roles');

    foreach( $children as $child )
    {

      $nodeName = strtolower($child->getName());

      if( in_array( $nodeName, $listElem ) )
      {
        $fields[] = $child;
      }
      elseif( in_array( $nodeName, $breakElem ) )
      {
        // don't collect values in this elements
        break;
      }
      else
      {
        $fields = $this->extractListLayoutFields( $fields, $child );
      }

    }

    return $fields;

  }//end public function extractListLayoutFields */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractListReferences( $references, $node  )
  {

    if(!$children = $node->children())
      return $references;

    $refElement  = array('reference');

    foreach( $children as $child )
    {

      $nodeName = strtolower($child->getName());

      if( in_array($nodeName, $refElement) )
      {
        $references[] = $child;
      }
      else
      {
        $references = $this->extractListReferences( $references, $child  );
      }

    }

    return $references;

  }//end public function extractListReferences */
  
  
/*//////////////////////////////////////////////////////////////////////////////
// Embeded Roles Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getEmbededRoles( $env )
  {

    if( isset($this->node->{$this->context}->layout ) )
    {
      $tmp = $this->extractEmbededRoles( array(), $this->node->{$this->context}->layout );

      $fieldClassName = $this->builder->getNodeClass( 'UiListEmbededRole' );
      $roles     = array();

      foreach( $tmp as $role )
      {
        $roleNode         = new $fieldClassName( $role );
        $roles[$roleNode->name->name] = $roleNode;
      }

      $this->embededRoles = $roles;
      return $roles;

    }

    if( !isset( $this->node->{$this->context}->fields->field ) )
      return null;

    $roles     = array();
    $use            = $this->node->{$this->context}->fields;
    $fieldClassName = $this->builder->getNodeClass( 'UiListEmbededRole' );

    foreach( $use->field as $field )
    {

      if( isset($field['type']) && 'roles' == $field['type'] )
      {
        $roleNode         = new $fieldClassName( $role );
        $roles[$roleNode->name->name] = $roleNode;
      }

    }

    $this->embededRoles = $roles;
    return $roles;

  }//end public function getEmbededRoles */

  /**
   * check if a listing element has listing reference elements
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function hasEmbededRoles( $env )
  {

    if( isset( $this->node->{$this->context}->layout ) )
    {
      $roles = $this->node->{$this->context}->layout->xpath( ".//roles" );
      return (boolean)count($roles);
    }

    if( !isset( $this->node->{$this->context}->fields->field ) )
      return false;

    $roles = $this->node->{$this->context}->fields->xpath( './/field[@type="roles"]' );
    return (boolean)count($roles);

  }//end public function hasEmbededRoles */

  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getEmbededRole( $env, $key )
  {

    if( !$this->embededRoles )
      $this->getEmbededRoles( $env, $env->context );

    return isset( $this->embededRoles[$key] )
      ?$this->embededRoles[$key]
      :null;

  }//end public function getEmbededRole */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractEmbededRoles( $roles, $node  )
  {

    if( !$children = $node->children() )
      return $roles;

    $refElement  = array('roles');

    foreach( $children as $child )
    {

      $nodeName = strtolower($child->getName());

      if( in_array($nodeName, $refElement) )
      {
        $roles[] = $child;
      }
      else
      {
        $roles = $this->extractEmbededRoles( $roles, $child  );
      }

    }

    return $roles;

  }//end public function extractEmbededRoles */
  
  
////////////////////////////////////////////////////////////////////////////////
// Debug
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function debugData()
  {
    return 'node type: '.get_class($this).' context '.$this->context;
  }//end public function debugData */

}//end class LibGenfTreeNodeUiListing

