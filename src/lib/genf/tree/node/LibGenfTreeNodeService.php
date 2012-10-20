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
class LibGenfTreeNodeService
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

  /**
   * @var array<LibGenfTreeNodeUiListReference>
   */
  public $listReferences = array();

  /**
   * @var array<LibGenfTreeNodeUiListEmbededRole>
   */
  public $embededRoles   = array();

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameService( $this->node );

    $this->management = $this->builder->getManagement( trim( $this->node['source'] ) );

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function getIdField( )
  {

    if( isset( $this->node->id['name'] ) )
      return trim($this->node->id['name']);

    return null;

  }//end public function getIdField */
  
  /**
   * @return string
   */
  public function getValueField( )
  {

    if( isset( $this->node->value['name'] ) )
      return trim($this->node->value['name']);

    return null;

  }//end public function getValueField */
  
  /**
   * @return string
   */
  public function getLabelField( )
  {

    if( isset( $this->node->auto_label['name'] ) )
      return trim($this->node->auto_label['name']);

    return null;

  }//end public function getValueField */
  
  /**
   * @return [LibGenfTreeNodeFilterCheck]
   */
  public function getFilter( )
  {

    if( !isset( $this->node->filter->check ) )
    {
      return array();
    }

    $checks = array();

    foreach( $this->node->filter->check as $check  )
    {
      $checks[] = new LibGenfTreeNodeFilterCheck( $check );
    }

    return $checks;

  }//end public function getFilter */


  /**
   * Prüfen ob filter vorhanden sind
   *
   * @return string
   */
  public function hasFilter(  )
  {

    return isset($this->node->filter->check);

  }//end public function hasFilter */

  /**
   * Anfragen ob es Parameter für die Filter gibt
   *
   * @return array<LibGenfTreeNodeParam>
   */
  public function getFilterParams(  )
  {

    if( !isset( $this->node->filter->params ) )
      return null;

    $params = array();

    foreach( $this->node->filter->params->param as $param )
    {
      $params[] = new LibGenfTreeNodeParam( $param );
    }

    return $params;

  }//end public function getFilterParams */

  /**
   * Extrahieren der tabellen fields aus dem ui layout
   *
   * @param string $context der listing context
   *
   * @return [LibGenfTreeNodeUiListField]
   */
  public function getFields( )
  {

    $context         = $this->context;
    $fieldClassName  = $this->builder->getNodeClass( 'UiListField' );

    $fields = array();

    if( !isset($this->node->fields->field) )
      return array();

    foreach( $this->node->fields->field as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getFields */

  /**
   * @return array<LibGenfTreeNodeFieldOrder>
   *
   */
  public function getOrder(  )
  {

    $order = array();
    if( isset($this->node->order_by->field ) )
    {
      foreach( $this->node->order_by->field as $field )
      {
        $order[] = new LibGenfTreeNodeFieldOrder( $field );
      }
      return $order;
    }

    return null;

  }//end public function getOrder */
  
  
  /**
   * @return string
   */
  public function getSearchType( )
  {
    
    return isset( $this->node->search['type'] )
      ? trim($this->node->search['type'])
      : 'contains';
      
  }//end  public function getSearchType */

  /**
   * Wir für die Switch Contexte benötigt
   * @return null
   */
  public function getColorSource()
  {
    return null;
  }//end public function getColorSource */

  /**
   * Wir für die Switch Contexte benötigt
   * @return null
   */
  public function getSortCols()
  {
    return null;
  }//end public function getSortCols */
  
  /**
   * @return array
   */
  public function getRoles()
  {

    if( !isset( $this->roles->role ) )
      return array();

    $roles = array();

    foreach( $this->roles->role as $role )
    {
      $roles[] = trim($role['name']);
    }

    return $roles;

  }//end public function getRoles */

  /**
   * @return array<LibGenfTreeNodeServiceReference>
   */
  public function getReferences()
  {

    $management = $this->management;

    $refList = array();

    if( isset( $this->node->references->reference ) )
    {

      foreach( $this->node->references->reference as $reference )
      {
        $refList[] = new LibGenfTreeNodeServiceReference
        (
          $reference,
          $management->entity->getReference( trim($reference['name']) ) ,
          $management
        );
      }

    }
    else
    {

      $references = $management->entity->getReferences();

      foreach( $references as $reference )
      {
        $refList[] = new LibGenfTreeNodeServiceReference
        (
          null,
          $reference,
          $management
        );
      }

    }

    return $refList;

  }//end public function getReferences */

  /**
   * @return array
   */
  public function getSearchCols()
  {
    
    return array();
    
  }//end public function getSearchCols */
  
  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   * @return array<LibGenfTreeNodeUiListReference>
   */
  public function getListingReferences( $env, $context )
  {


    if( !isset( $this->node->references->ref ) )
      return null;

    $references     = array();
    $fieldClassName = $this->builder->getNodeClass( 'UiListReference' );

    foreach( $this->node->references->reference as $ref )
    {

      $lRef       = new $fieldClassName( $ref );
      $lRef->ref  = $env->management->getReference( trim( $ref['target'] ), $ref );

      $references[$lRef->name->name] = $lRef;

    }

    $this->listReferences = $references;
    return $references;

  }//end public function getListingReferences */


  /**
   * @param LibGenfEnvManagement $env
   * @param string $context
   */
  public function getEmbededRoles( $env, $context )
  {

    if( !isset( $this->node->roles ) )
      return null;

    $roles     = array();
    $fieldClassName = $this->builder->getNodeClass( 'UiListEmbededRole' );

    foreach( $this->node->roles as $role )
    {

      $roleNode         = new $fieldClassName( $role );
      $roles[$roleNode->name->name] = $roleNode;

    }

    $this->embededRoles = $roles;
    return $roles;

  }//end public function getEmbededRoles */


}//end class LibGenfTreeNodeService

