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
 * Node zum repräsentieren der Order Cols im List Layout
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiListOrderCol
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getAttrName( )
  {
    // if not exists, that's an error
    if( !isset( $this->node['order_attr'] ) )
      return null;

    return trim( $this->node['order_attr'] );

  }//end public function getAttrName */
  
  /**
   *
   * @return string
   */
  public function getRefAttrName( )
  {
    // optional
    if( !isset( $this->node['order_ref_attr'] ) )
      return null;

    return trim($this->node['order_ref_attr']);

  }//public function getRefAttrName */
  
  /**
   *
   * @return string
   */
  public function getAlignment( )
  {
    // optional
    if( !isset( $this->node['order'] ) )
      return null;

    return trim($this->node['order']);

  }//public function getAlignment */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @return TContextAttribute
   */
  public function getOrderAttribute( $env )
  {

    $fieldName     = $this->getAttrName();
    $refFieldName  = $this->getRefAttrName();
    $src           = $this->getReference();
    $refType       = $this->getRefType();
    
    $management = $env->getContextMgmt();
    
    $field = $management->getField( $fieldName, $src, $refType );
    
    if( !$field )
    {
      Log::warn( "Missing order Field: {$fieldName} src: {$src} type: {$refType} ".$env->debugData() );
      Debug::console( "Missing order Field: {$fieldName} src: {$src} type: {$refType}".$env->debugData() );
      return null;
    }
    
    if( !$refFieldName )
    {
      $field->contextKeyName = $management->name->source;
      return $field;
    }
      
    $targetMgmt = $field->attribute->targetManagement();
      
    $refField = $targetMgmt->getField( $refFieldName );
    
    if( $src )
      $refField->contextKeyName = $src;
    else 
      $refField->contextKeyName = $targetMgmt->name->source;
    
    
    return $refField;

  }//end public function getOrderAttribute */
  
  /**
   * @param LibGenfTreeNodeManagement $mgmt
   * @return TContextAttribute
   */
  public function getOrderAttributeByMgmt( $mgmt )
  {

    $fieldName     = $this->getAttrName();
    $refFieldName  = $this->getRefAttrName();
    $src           = $this->getReference();
    //$refType       = $this->getRefType();
    
    $field = $mgmt->getField( $fieldName, $src );
    
    if( !$field )
    {
      Debug::console( 'Missing Field '.$fieldName.' src: '.$src );
      return null;
    }
    
    if( !$refFieldName )
    {
      $field->contextKeyName = $mgmt->name->source;
      return $field;
    }
      
    $targetMgmt = $field->attribute->targetManagement();
      
    $refField = $targetMgmt->getField( $refFieldName );
    
    if( !$refField )
    {
      Debug::console( 'Missing Ref Field '.$refFieldName );
      return null;
    }
    
    if( $src )
      $refField->contextKeyName = $src;
    else 
      $refField->contextKeyName = $targetMgmt->name->name;
    
    
    return $refField;

  }//end public function getOrderAttribute */
  
  /**
   * @param LibGenfEnvManagement $env
   * @return TContextAttribute
   */
  public function getAttribute( $env )
  {
    
    $fieldName = $this->getAttrName();
    $src       = $this->getRefAttrName();
    $refType   = $this->getRefType();
    
    return $env->getContextMgmt()->getField( $fieldName, $src, $refType );

  }//end public function getAttribute */


  /**
   * @return string
   */
  public function getReference( )
  {

    if( !isset( $this->node['order_ref'] ) )
      return null;

    return trim( $this->node['order_ref'] );

  }//public function getReference */

  /**
   * @return string
   */
  public function getRefType( )
  {
    // optional
    if(!isset( $this->node['order_ref_type'] ) )
      return null;

    return trim($this->node['order_ref_type']);

  }//public function getRefType */

}//end class LibGenfTreeNodeUiListOrderField

