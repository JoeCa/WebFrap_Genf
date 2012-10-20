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
class LibGenfTreeNodeUiListField
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  public $origFieldName = null;

  /**
   *
   * @var LibGenfTreeNodeReference
   */
  public $ref = null;
  
  /**
   *
   * @var TContextAttribute
   */
  public $field = null;
  
////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfName $name
   * @param $params
   */
  public function __construct( $node, $field = null )
  {

    $this->builder  = LibGenfBuild::getInstance();

    if( $this->rootType )
      $this->root   = $this->builder->getRoot($this->rootType);

    $this->field = $field;

    $this->validate( $node );
    $this->prepareNode( array() );
    $this->loadChilds( );

  }//end public function __construct */
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return TContextAttribute
   */
  public function getField( )
  {
    
    if( $this->field )
      return $this->field;

    return ( is_object($this->node) && $this->node instanceof TContextAttribute )
      ? $this->node
      : null;

  }//end public function getField */
  
  /**
   * @return string
   */
  public function uiElement( )
  {
    
    if( !is_object($this->node) )
      return null;
    
    ///TODO errorhandling
    if( isset( $this->node->ui_element ) )
      return trim( $this->node->ui_element['type'] );

    return isset( $this->node['ui_element'] )
      ? trim( $this->node['ui_element'] )
      : null;

  }//end public function uiElement */

  /**
   * @return LibGenfTreeNodeUiElementField
   */
  public function getUiElement( )
  {

    // optional
    if( !isset( $this->node->ui_element ) )
      return null;

    return new LibGenfTreeNodeUiElementField( $this->node->ui_element );

  }//end public function getUiElement */

  /**
   * @return string
   */
  public function fieldName( )
  {
    
    // if not exists, that's an error
    if( !isset( $this->node['name'] ) )
      return null;

    return trim( $this->node['name'] );

  }//end public function fieldName */
  
  /**
   * @param LibGenfEnvManagement $env
   * @return TContextAttribute
   */
  public function getAttribute( $env )
  {
    
    $fieldName = $this->fieldName();
    $src       = $this->reference();
    $refType   = $this->refType();
    
    return $env->getFieldObj( $fieldName, $src, $refType );

  }//end public function getAttribute */

  /**
   * @return string
   * @deprecated use reference instead
   */
  public function src( )
  {

    if( isset( $this->node['ref'] ) )
    {
      return trim( $this->node['ref'] );
    }

    // optional
    if( !isset( $this->node['src'] ) )
      return null;

    return trim( $this->node['src'] );

  }//public function src */

  /**
   * @return string
   */
  public function reference( )
  {

    if( isset( $this->node['ref'] ) )
    {
      return trim($this->node['ref']);
    }

    // optional
    if( !isset( $this->node['src'] ) )
      return null;

    return trim( $this->node['src'] );

  }//public function reference */

  /**
   * @return string
   */
  public function refType( )
  {
    
    // optional
    if( !isset( $this->node['ref_type'] ) )
      return null;

    return trim($this->node['ref_type']);

  }//public function refType */

  /**
   * displayField is used to tell the generator which field has to be used
   * by displaying simple references
   *
   * eg: if employee.id_cost_center has no display node, then you can set here
   * the name of the field from the reference table, that should be displayed
   * or you can overwrite the fieldname if you don't like the original defined
   * field in the display node
   *
   * @return string
   */
  public function displayField( )
  {
    
    // optional
    if( !isset( $this->node['field'] ) )
      return null;

    return trim($this->node['field']);

  }//public function displayField */

  /**
   * @return string
   */
  public function action( )
  {
    
    // optional
    if( !isset( $this->node['action'] ) )
      return null;

    return trim($this->node['action']);

  }//public function action */
  
  /**
   * @return LibGenfTreeNodeTriggerAction
   */
  public function getActionTrigger( )
  {
    
    // optional
    if( !isset( $this->node->action ) )
      return null;
      
    $type = SParserString::subToCamelCase( trim($this->node->action['type']) );
    
    $className = 'LibGenfTreeNodeTrigger_'.$type;
    
    if( !Webfrap::classLoadable($className) )
    {
      $this->builder->dumpError( 'Missing Action trigger type '.$type );
      return null;
    }

    return new $className( $this->node->action );

  }//public function getActionTrigger */

  /**
   * @return false
   */
  public function isReadOnly()
  {
    
    if( !isset( $this->node['readonly'] ) )
      return false;

    return (trim($this->node['readonly']) == 'true');
    
  }//end public function isReadOnly */
  
  /**
   * @return false
   */
  public function isRequired()
  {
    
    if( !isset( $this->node['required'] ) )
      return false;

    return (trim($this->node['required']) == 'true');
    
  }//end public function isRequired */
  
  /**
   * @return false
   */
  public function isHidden()
  {
    return false;
  }//end public function isHidden */
  
  /**
   * @return false
   */
  public function isDisabled()
  {
    
    if( !isset( $this->node['disabled'] ) )
      return false;

    return (trim($this->node['disabled']) == 'true');
    
  }//end public function isDisabled */
  
  /**
   * @return string
   */
  public function defaultValue()
  {
    
    if( !isset( $this->node->default ) )
      return null;

    return trim($this->node->default);
    
  }//end public function defaultValue */
  
  
  /**
   * 
   */
  public function getDebugDump()
  {
    
    $dbgData = get_class( $this );

    $dbgData .= $this->getUiElement();
    
    
    return $dbgData;
  }

}//end class LibGenfTreeNodeUiListField

