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
class LibGenfTreeNodeEntityUi
  extends LibGenfTreeNode
{

  /**
   *
   * @var array
   */
  public $sizeColMap = array
  (
     'small'   => 1,
     'medium'  => 2,
     'default' => 2,
     'full'    => 3,
  );

  /**
   *
   * @var array
   */
  public $styleMap = array
  (
    'plain' => 'plain',
    'tab'   => 'window_tab',
  );

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  public $grouping = null;

  /**
   *
   * Enter description here ...
   * @var string
   */
  public $context = null;

  /**
   *
   * Enter description here ...
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

/*//////////////////////////////////////////////////////////////////////////////
// property
//////////////////////////////////////////////////////////////////////////////*/
  
  
  /**
   *
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context )
  {

    if( !isset( $this->node->list ) )
    {
      return null;
    }

    if(!$uiClassName = $this->builder->getNodeClass( 'UiListing'.ucfirst($context) ) )
      $uiClassName = $this->builder->getNodeClass( 'UiListing' );

    $ui = new $uiClassName( $this->node->list, $context );

    return $ui;

  }//end public function getListUi */

  /**
   * @param string $context
   * @param LibGenfTreeNodeManagement $management
   * 
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context, $management = null )
  {

    if( !isset($this->node->form) )
    {
      return null;
    }
    
    if( !$management )
    {
      $management = $this->management;
    }

    $ui = new LibGenfTreeNodeUiForm( $this->node->form, $context );
    $ui->management = $management;
    
    return $ui;

  }//end public function getFormUi */
  

/*//////////////////////////////////////////////////////////////////////////////
// property
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $key
   * @param string $attribute
   * @return string
   */
  public function property( $key, $attribute = null )
  {
    if( $attribute )
    {
      return isset($this->node->properties->{$key}[$attribute])
        ?trim($this->node->properties->{$key}[$attribute])
        :null;
    }
    else
    {
      return isset($this->node->properties->{$key})
        ?trim($this->node->properties->{$key})
        :null;
    }

  }//end public function property */

////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $context
   */
  public function windowSizeKey( $context )
  {

    if( !$key = $this->windowSize( $context ) )
    {
      return null;
    }

    return isset( $this->sizeColMap[$context] )
     ?    $this->sizeColMap[$context]
     :    null;

  }//end public function windowSize */

  /**
   * @param string $context
   */
  public function windowSize( $context )
  {

    if( !isset( $this->node->$context->window['size']  ) )
    {
      return isset($this->node->default->window['size'])
       ? $this->node->default->window['size']
       : null;
    }

    return trim($this->node->$context->window['size']);

  }//end public function windowSize */

/*//////////////////////////////////////////////////////////////////////////////
//  Body Information
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   */
  public function bodyStyle( $context )
  {

    if( !isset( $this->node->$context->body['type']  ) )
    {
      return isset($this->node->default->body['type'])
       ? $this->node->default->body['type']
       : null;
    }

    return trim($this->node->$context->body['type']);

  }//end public function bodyStyle */

  /**
   * @param string $context
   */
  public function bodyStyleClass( $context )
  {

    if( !$key = $this->bodyStyle( $context ) )
      return null;

    return isset($this->styleMap[$key])
      ? $this->styleMap[$key]
      : null;

  }//end public function bodyStyleClass */

/*//////////////////////////////////////////////////////////////////////////////
//  Menu Information
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   */
  public function globalMenuType(  )
  {

    return isset($this->node->global->menutree['type'])?trim($this->node->global->menutree['type']):null;

  }//end public function menuType */

  /**
   * @return LibGenfTreeNodeUiMenu
   */
  public function getMenu( )
  {

    if(!isset( $this->node->menu))
      return null;

    $fieldClassName = $this->builder->getNodeClass('UiMenu');
    return new $fieldClassName( $this->node->menu );

  }//end public function getMenu */

/*//////////////////////////////////////////////////////////////////////////////
//  categories
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param $context
   * @param $autoAppendMeta
   * /
  public function getFormCategories( $context, $autoAppendMeta = false )
  {

    if( !isset($this->node->form->$context->use) )
      return null;

    if( !isset($this->node->form->$context->use->category) )
      return array();

    $cats = array();

    $use = $this->node->form->$context->use;

    foreach( $use->category as $category )
    {
      $cats[] = trim($category['name']);
    }

    if( $autoAppendMeta  )
    {
      if(!in_array('meta',$cats))
        $cats[] = 'meta';
    }

    return $cats;


  }//end public function getFormCategories */

  /**
   * @param string $context
   * /
  public function getFormDyntextKeys( $context )
  {

    $layout = null;
    $keys = array();

    if( isset( $this->node->form->$context->layout ) )
    {
      $layout = $this->node->form->$context->layout;
    }
    elseif( isset( $this->node->form->crud->layout ) )
    {
      $layout = $this->node->form->crud->layout;
    }

    if( !$layout )
      return $keys;

    $dynTexts = $layout->xpath('.//dyntext');

    foreach( $dynTexts as $dynText )
    {
      $keys[] = trim($dynText['key']);
    }


    return $keys;

  }//end public function getDyntextKeys */

  /**
   * @param string $context
   * @return SimpleXmlElement
   */
  public function hasFormLayout( $context = null )
  {

    
    if( $context )
    {
      if( isset( $this->node->form ) )
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    else 
    {
      if( isset( $this->node->form->$context->layout ) )
      {
        return true;
      }
      elseif( isset( $this->node->form->crud->layout ) )
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    


  }//end public function hasFormLayout */
  


  /**
   * check the layout type
   * @param string $context
   * @param string $type
   */
  public function isFormLayoutType( $context, $type )
  {

    if(!isset($this->node->form->$context->layout['type']))
      return false;

    return strtolower(trim($this->node->form->$context->layout['type'])) == strtolower($type);

  }//end public function isFormLayoutType */

  /**
   * @param string $context
   * @return array
   * /
  public function getFormFields( $context )
  {

    $raw = null;

    if( isset( $this->node->form->$context->layout ) )
    {
      $raw = $this->extractFormLayoutFields(array(), $this->node->form->$context->layout );
      //Debug ::console('form '.$context.' layout',$raw);
    }
    elseif( isset( $this->nnode->form->crud->layout ) )
    {
      $raw = $this->extractFormLayoutFields(array(), $this->node->form->crud->layout );
      //Debug ::console('form crud layout',$raw);
    }

    if( is_null($raw) )
      return  null;

    $fieldClassName = $this->builder->getNodeClass('UiFormField');
    $fields   = array();

    foreach( $raw as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getFormFields */

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

/*//////////////////////////////////////////////////////////////////////////////
// Filter Methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractListLayoutFields( $fields, $node )
  {

    if(!$children = $node->children())
      return $fields;

    $listElem = array('field','value');

    foreach( $children as $child )
    {

      $nodeName = strtolower($child->getName());

      if( in_array($nodeName, $listElem) )
        $fields[] = $child;
      else
        $fields = $this->extractListLayoutFields( $fields, $child );
    }

    return $fields;

  }//end public function extractListLayoutFields */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractFormLayoutFields( $fields, $node )
  {

    if(!$children = $node->children())
      return $fields;

    $meta = false;

    foreach( $children as $child )
    {

      if( 'field' == $child->getName() )
      {
        $fields[] = $child;
      }
      else if( 'category' == $child->getName() )
      {

        if( 'meta' == trim($child['name']) )
          $meta = true;

        $fields = $this->getFormCategoryFields( $fields, trim($child['name']) );
      }
      else
      {
        $fields = $this->extractFormLayoutFields( $fields, $child );
      }
    }

    if(!$meta)
      $fields = $this->getFormCategoryFields( $fields, 'meta' );

    return $fields;

  }//end public function extractFormLayoutFields */

  /**
   * @param string $context
   * /
  public function getListActions( $context )
  {

    if( !isset($this->node->list->$context->actions ) )
      return null;

    $actions = array();

    $use = $this->node->list->$context->actions;

    $className = $this->builder->getNodeClass('UiMenuEntry');

    foreach( $use->node as $action )
    {
      $actions[] = new $className( $action );
    }

    return $actions;

  }//end public function getListActions */

/*//////////////////////////////////////////////////////////////////////////////
// grouping Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return array
   * /
  public function hasGroupings( $context )
  {

    if( isset($this->node->list->$context->groupings->group ) )
    {
      return true;
    }
    elseif( isset($this->node->list->groupings->group ) )
    {
      return true;
    }
    else
    {
      return false;
    }

  }//end public function hasGroupings


  /**
   * @param string $context
   * @return array
   * /
  public function getListGroupings( $context )
  {

    if( isset($this->node->list->$context->groupings ) )
    {
      $use = $this->node->list->$context->groupings;
    }
    elseif( isset($this->node->list->groupings) )
    {
      $use = $this->node->list->groupings;
    }
    else
    {
      return array();
    }

    $groups = array();

    $className = $this->builder->getNodeClass('UiGroup');

    foreach( $use->group as $group )
    {
      $groupObj = new $className( $group );
      $groupObj->context = $context;
      $groups[] = $groupObj;
    }

    return $groups;

  }//end public function getListGroupings


  /**
   * @param string $context
   * /
  public function groupingRequired( $context )
  {

    $groupings = $this->getListGroupings( $context );

    foreach( $groupings as $group )
    {
      if( $group->required() )
        return true;
    }

    return false;

  }//end public function groupingRequired */


/*//////////////////////////////////////////////////////////////////////////////
// grouping Methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $type
   */
  public function mgmtAction( $type )
  {

    $clean = $this->node->xpath('./actions[@type=\'clean\']');


    $action = $this->node->xpath('./actions/action[@type="'.$type.'"]');

    if(!$action)
      return !(boolean)count($clean);

    $action = $action[0];

    if(!isset($action['status']))
      return !(boolean)count($clean);

    if( 'false' == trim($action['status']) )
      return false;

    return true;


  }//end public function mgmtAction */

}//end class LibGenfTreeNodeEntityUi

