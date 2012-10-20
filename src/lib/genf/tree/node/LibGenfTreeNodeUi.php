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
class LibGenfTreeNodeUi
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attribute
//////////////////////////////////////////////////////////////////////////////*/

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
   * Der Verweis auf einen mgmt node
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

  /**
   * Alle Referenzen die im Listenelement für den aktuellen Kontext
   * eingebunden sind
   * @var array
   */
  public $listReferences = array();

  /**
   *
   * @var array
   */
  public $embededRoles = array();


  /**
   * cache all existing tabs
   * @var array
   */
  public $tabs = null;

  /**
   * cache all existing tabs
   * @var array
   */
  public $subUIs = array();

  /**
   * Fallback Knoten für den fall das im aktiven knoten keine passenden
   * werte vorhanden sind
   *
   * @var LibGenfTreeNodeUi
   */
  public $fallback = null;

/*//////////////////////////////////////////////////////////////////////////////
// Protected Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  protected $nodeMenu = null;


/*//////////////////////////////////////////////////////////////////////////////
// Method
//////////////////////////////////////////////////////////////////////////////*/
  /**
   * @param SimpleXmlNode $node
   */
  protected function validate( $node )
  {

    $this->valid  = true;
    $this->node   = $node;

    $this->nodeMenu = isset($this->node->menu)
      ? $this->node->menu
      : null;

  }//end protected function validate */

/*//////////////////////////////////////////////////////////////////////////////
// Method
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
      if( $this->fallback )
        return $this->fallback->getListUi( $context );
      else
        return null;
    }

    if( !$uiClassName = $this->builder->getNodeClass( 'UiListing'.ucfirst($context) ) )
      $uiClassName = $this->builder->getNodeClass( 'UiListing' );

    $ui = new $uiClassName( $this->node->list, $context );

    if( $this->fallback )
    {
      $ui->setFallback( $this->fallback->getListUi( $context) );
    }

    $ui->parent = $this;

    return $ui;

  }//end public function getListUi */
  
  /**
   * Checken ob explizit für einen definierten context ein List ui vorhanden ist
   * 
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function hasListUi( $context )
  {

    if( !isset( $this->node->list->{$context} ) )
    {
      if( $this->fallback )
        return $this->fallback->hasListUi( $context );
      else
        return false;
    }

    return true;

  }//end public function hasListUi */

  /**
   * @param string $context
   * @param LibGenfTreeNodeManagement $management
   *
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context, $management = null )
  {

    if( !$management )
      $management = $this->management;

    if( !isset( $this->node->form ) )
    {
      if( $this->fallback )
        return $this->fallback->getFormUi( $context, $management );
      else
        return null;
    }

    $ui             = new LibGenfTreeNodeUiForm( $this->node->form, $context );
    $ui->management = $management;

    if( $this->fallback )
    {
      $ui->setFallback( $this->fallback->getFormUi( $context, $management ) );
    }
    
    $ui->parent = $this;

    return $ui;

  }//end public function getFormUi */

  /**
   * @return SimpleXmlElement
   */
  public function hasFormLayout()
  {

    if( isset( $this->node->form ) )
      return true;

    if( !$this->fallback )
      return false;

    return $this->fallback->hasFormLayout();

  }//end public function hasFormLayout

  /**
   * @param string $key
   * @return array<LibGenfTreeNodeItem>
   */
  public function getFormItems( )
  {

    $layouts = $this->node->xpath( './form//layout' );

    if( !$layouts )
    {

      if( $this->fallback )
      {
        return $this->fallback->getFormItems( );
      }

      return null;
    }

    $items = $this->node->xpath( './form//layout//item' );

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

  }//end public function getFormItems */

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

    $properties = $this->node->properties;
    if( $attribute ) 
    {
      if( isset( $properties->{$key}[$attribute] ) )
        return trim( $properties->{$key}[$attribute] );
    } 
    else 
    {
      if( isset( $properties->{$key} ) )
        return trim( $properties->{$key} );
    }
 
    if( $this->fallback && is_object( $this->fallback ) )
      return $this->fallback->property( $key, $attribute );
 
    return null;

  }//end public function property */

/*//////////////////////////////////////////////////////////////////////////////
// implement parent nodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $context
   */
  public function windowSizeKey( $context )
  {

    if( !$key = $this->windowSize( $context ) )
    {

      if( $this->fallback )
      {
        return $this->fallback->windowSizeKey( $context );
      }

      return null;
    }

    return isset($this->sizeColMap[$context])
     ?    $this->sizeColMap[$context]
     :    null;

  }//end public function windowSize */

  /**
   * @param string $context
   * @return string
   */
  public function windowSize( $context )
  {

    if( !isset( $this->node->$context->window['size']  ) )
    {

      if( isset($this->node->default->window['size']) )
      {
        return $this->node->default->window['size'];
      }

      if( $this->fallback )
      {
        return $this->fallback->windowSize( $context );
      }

      return null;
    }

    return trim($this->node->$context->window['size']);

  }//end public function windowSize */

/*//////////////////////////////////////////////////////////////////////////////
//  Body Information
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @return string
   */
  public function bodyStyle( $context )
  {

    if( !isset( $this->node->$context->body['type']  ) )
    {

      if( isset($this->node->default->body['type']) )
      {
        return $this->node->default->body['type'];
      }

      if( $this->fallback )
      {
        return $this->fallback->bodyStyle( $context );
      }

      return null;

    }

    return trim($this->node->$context->body['type']);

  }//end public function bodyStyle */

  /**
   * @param string $context
   */
  public function bodyStyleClass( $context )
  {

    if( !$key = $this->bodyStyle( $context ) )
    {
      if( !$this->fallback )
      {
        return null;
      }
      else
      {
        return $this->fallback->bodyStyleClass( $context );
      }
    }

    return isset($this->styleMap[$context])
      ? $this->styleMap[$context]
      : null;

  }//end public function bodyStyleClass */

/*//////////////////////////////////////////////////////////////////////////////
//  Menu Information
//////////////////////////////////////////////////////////////////////////////*/

  /**
   */
  public function globalMenuType(  )
  {

    if( !isset($this->node->global->menutree['type']) )
    {
      if( !$this->fallback )
      {
        return null;
      }
      else
      {
        return $this->fallback->globalMenuType( );
      }
    }

    return trim($this->node->global->menutree['type']);

  }//end public function globalMenuType */

  /**
   * @return LibGenfTreeNodeUiMenu
   */
  public function getMenu( )
  {

    if( is_null( $this->nodeMenu) )
    {

      if( !$this->fallback )
      {
        return null;
      }
      else
      {
        return $this->fallback->getMenu();
      }

    }

    $fieldClassName = $this->builder->getNodeClass('UiMenu');
    return new $fieldClassName( $this->nodeMenu );

  }//end public function getMenu */

/*//////////////////////////////////////////////////////////////////////////////
//  categories
//////////////////////////////////////////////////////////////////////////////*/


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
   * @param string $context
   * @return array
   */
  public function getFormReferences( $context, $all = false )
  {

    // wenn hier keine form information vorhanden ist, prüfen
    // ob es einen fallback knoten mit forminformationen gibt
    if( !isset( $this->node->form ) )
    {

      if( $this->fallback )
        return $this->fallback->getFormReferences( $context, $all );
      else
        return null;

    }


    $raw = null;

    if( $all && isset($this->node->form) )
    {
      $raw = $this->extractFormLayoutReferences( array(), $this->node->form );
    }
    else
    {
      if( isset( $this->node->form->$context->layout ) )
      {
        $raw = $this->extractFormLayoutReferences( array(), $this->node->form->$context->layout );
        //Debug ::console('form '.$context.' layout',$raw);
      }
      
      // prüfen ob crud überhaupt verwendet wird!
      elseif( isset( $this->node->form->crud->layout ) )
      {
        $raw = $this->extractFormLayoutReferences( array(), $this->node->form->crud->layout );
        //Debug ::console('form crud layout',$raw);
      }
    }

    if( is_null($raw) )
      return  array();


    return $raw;

  }//end public function getFormReferences */

/*//////////////////////////////////////////////////////////////////////////////
// Controll
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return LibGenfTreeNodeUiControls
   */
  public function getControls()
  {

    if( !isset( $this->node->controls ) )
      return null;

    return new LibGenfTreeNodeUiControls( $this->node->controls );

  }//end public function getControls */

/*//////////////////////////////////////////////////////////////////////////////
// Filter Methodes
//////////////////////////////////////////////////////////////////////////////*/



  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getListingReferences( $env, $context )
  {

    // checken ob es für den context auf dem Management Node eine layout beschreibung gibt
    if( isset($this->node->list->$context->layout ) )
    {
      $tmp = $this->extractListReferences( array(), $this->node->list->$context->layout );

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
        
        //Debug::console( 'GOT LIST REF '.$lRef->name->name.' in this '.$env->name->name.' context: '.$context.' '.$dbgTarget );

        $references[$lRef->name->name] = $lRef;

      }

      $this->listReferences = $references;
      return $references;

    }

    if( !isset( $this->node->list->$context->fields->field ) )
      return null;

    $references     = array();
    $use            = $this->node->list->$context->fields;
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

    if( isset($this->node->list->$context->layout ) )
    {
      $tmp = $this->extractListReferences( array(), $this->node->list->$context->layout );
      return (boolean)count($tmp);
    }

    if( !isset($this->node->list->$context->fields->field ) )
      return false;

    $references     = array();
    $use            = $this->node->list->$context->fields;

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
      $this->getListingReferences( $env, $env->context );

    return isset( $this->listReferences[$key] )
      ?$this->listReferences[$key]
      :null;

  }//end public function getListingReference */


/*//////////////////////////////////////////////////////////////////////////////
// Embeded Roles Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getEmbededRoles( $env, $context )
  {

    if( isset($this->node->list->$context->layout ) )
    {
      $tmp = $this->extractEmbededRoles( array(), $this->node->list->$context->layout );

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

    if( !isset( $this->node->list->$context->fields->field ) )
      return null;

    $roles     = array();
    $use            = $this->node->list->$context->fields;
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
  public function hasEmbededRoles( $env, $context )
  {

    if( isset( $this->node->list->$context->layout ) )
    {
      $roles = $this->node->list->$context->layout->xpath( ".//roles" );
      return (boolean)count($roles);
    }

    if( !isset( $this->node->list->$context->fields->field ) )
      return false;

    $roles = $this->node->list->$context->fields->xpath( './/field[@type="roles"]' );
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
//
////////////////////////////////////////////////////////////////////////////////

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

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractFormLayoutFields( $fields, $node )
  {

    if(!$children = $node->children())
      return $fields;

    $meta = false;

    $fieldNames = array('field','value',);

    foreach( $children as $child )
    {

      if( in_array( $child->getName(), $fieldNames )  )
      {
        $fields[] = $child;
      }
      else if( 'category' == $child->getName() )
      {

        // prüfen ob die meta kategory angehängt wurde
        // diese wird in einigen fällen zum speichern benötigt
        if( 'meta' == trim($child['name']) )
          $meta = true;

        $fields = $this->getFormCategoryFields( $fields, trim($child['name']) );
      }
      else
      {
        $fields = $this->extractFormLayoutFields( $fields, $child );
      }
    }

    // wenn die metakategorie nicht vorhanden ist, wird sie automatisch
    // ans ende des formulars gepackt
    if(!$meta)
      $fields = $this->getFormCategoryFields( $fields, 'meta' );

    return $fields;

  }//end public function extractFormLayoutFields */



  /**
   * @param array $references
   * @param SimpleXmlElement $node
   * @param string $actualTab name der des aktuellen tabs
   */
  public function extractFormLayoutReferences( $references, $node, $actualTab = null )
  {

    if( !$children = $node->children() )
      return $references;


    $tagNames = array( 'reference' );

    foreach( $children as $child )
    {

      // der name des tabs wird benötigt nur so können wie später saubere
      // acls implementieren und ein dynamisches layout
      if( strtolower($child->getName()) == 'tab' )
      {
        $actualTab = trim($child['name']);
      }

      if( in_array( $child->getName(), $tagNames )  )
      {
        $references[] = array( $child, $actualTab ) ;
      }
      else
      {
        $references = $this->extractFormLayoutReferences( $references, $child, $actualTab );
      }

    }

    return $references;

  }//end public function extractFormLayoutReferences */

/*//////////////////////////////////////////////////////////////////////////////
// grouping Methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * <ui>
   *   <ations>
   *     <action type="list_type|action_type" status="true|false" />
   *  </action>
   * </ui>
   * 
   * @param string $type
   * @return string
   */
  public function mgmtAction( $type )
  {
    
    $mappedType = null;
    $actionMap  = array
    (
      'table'      => 'list',
      'treetable'  => 'list',
      'tree'       => 'list',
      'blocklist'  => 'list',
      'grid'       => 'list'
    );
    
    if( isset( $actionMap[$type] ) )
    {
      $mappedType = $actionMap[$type];
    }

    $status = true;

    if( $this->node->xpath('./actions[@status="false"]')  )
    {
      $status = false;
    }

    $action = $this->node->xpath('./actions/action[@type="'.$type.'"]');

    if( !$action )
    {
      if( $mappedType )
      {
        $action = $this->node->xpath('./actions/action[@type="'.$type.'"]');
        
        if( !$action )
          return $status;
      }
      else 
      {
        return $status;
      }
      
    }

    $action = $action[0];

    if( !isset( $action['status'] ) )
      return true;

    if( 'false' == trim($action['status']) )
      return false;
    else
      return true;


    /*
    $clean = $this->node->xpath('//actions[@type=\'clean\']');


    $action = $this->node->xpath('//actions/action[@type="'.$type.'"]');

    if(!$action)
      return !(boolean)count($clean);

    $action = $action[0];

    if(!isset($action['status']))
      return !(boolean)count($clean);

    if( 'false' == trim($action['status']) )
      return false;

    return true;
    */


  }//end public function mgmtAction */

/*//////////////////////////////////////////////////////////////////////////////
// Visibility
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Visibility node getter
   * @return LibGenfTreeNodeUiVisibility oder null im fehlerfall
   */
  public function getVisibility()
  {

    if( !isset( $this->node->visibility ) )
      return null;

    $className = $this->builder->getNodeClass( 'UiVisibility' );

    return new $className( $this->node->visibility );

  }//end public function getVisibility */

}//end class LibGenfTreeNodeUi

