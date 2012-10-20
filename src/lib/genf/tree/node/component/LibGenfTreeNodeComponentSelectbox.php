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
class LibGenfTreeNodeComponentSelectbox
  extends LibGenfTreeNodeComponent
{
////////////////////////////////////////////////////////////////////////////////
// Attribute, Mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   * Check welche Datenquelle die Selectbox hat
   * Kann eine Datenbankabfrage, aber auch ein ENUM sein
   * Theoretisch sind noch mehr Datenquellen denkbar, aber
   * im Moment werden nur besagte beide implementiert
   * 
   * @return string
   */
  public function getDatasource()
  {
    
    if( !isset($this->node->data_source['type']) ) 
      return 'dbms';
      
    return trim( $this->node->data_source['type'] );
    
  }//end public function getDatasource */

  
  /**
   * Erfragen der Variante der Component
   * @return string
   */
  public function getVariant()
  {
    
    if( !isset($this->node->variant['name']) ) 
      return true;
      
    return trim( $this->node->variant['name'] );
    
  }//end public function getVariant */
  
  /**
   * Erfragen der Variante der Component
   * @return string
   */
  public function getFilterKey()
  {
    
    if( !isset($this->node->variant['key']) ) 
      return null;
      
    return trim( $this->node->variant['key'] );
    
  }//end public function getFilterKey */
  
  /**
   * Erfragen der Variante der Component
   * <dyn_filter name="id_category" key="access_key" />
   * @return string
   */
  public function isDynFiltered()
  {
    
    return isset( $this->node->dyn_filter );

  }//end public function isDynFiltered */
  
  /**
   * Erfragen der Variante der Component
   * <dyn_filter name="id_category" key="access_key" />
   * @return string
   */
  public function getDynFilterField()
  {
    
    return trim( $this->node->dyn_filter['name'] );

  }//end public function getDynFilterField */
  
  /**
   * Erfragen der Variante der Component
   * <dyn_filter name="id_category" key="access_key" />
   * @return string
   */
  public function getDynFilterKey()
  {
    
    return trim( $this->node->dyn_filter['key'] );

  }//end public function getDynFilterKey */

  /**
   * @return [LibGenfTreeNodeFilterCheck]
   */
  public function getFilter( )
  {

    $filter = array();
    
    if( isset($this->node->filter->check ) )
    {
      $filter = $this->node->filter;
    }

    if( !$filter )
    {
      return array();
    }
    
    $checks = array();
    
    foreach( $filter->check as $check  )
    {
      
      $className = 'LibGenfTreeNodeFilter_'.SParserString::subToCamelCase( $check['type'] );
      
      if(  !Webfrap::classLoadable($className) )
      {
        $this->builder->dumpError( "Invalid Filtercheck ".$check['type'] );
        continue;
      }
      
      $checks[] = new $className( $check );
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
   * @return array<LibGenfTreeNodeUiListField>
   */
  public function getFields( )
  {

    $context         = $this->context;
    $fieldClassName  = $this->builder->getNodeClass( 'UiListField' );


    $fields = array();
    
    $fields[] = new $fieldClassName( $this->node->id );

    foreach( $this->node->fields->field as $field )
    {
      $fields[] = new $fieldClassName( $field );
    }

    return $fields;

  }//end public function getFields */
  
  /**
   * @return string
   */
  public function getIdField()
  {
    
    return trim( $this->node->id['name'] );
  }//end public function getIdField */
  
  /**
   * Wir für die Switch Contexte benötigt
   * @return null
   */
  public function getSortCols()
  {
    return null;
  }//end public function getSortCols */
  
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
   * @param LibGenfTreeNodeManagement $mgmt
   * @return LibGenfTreeNodeAttribute 
   */
  public function getGroupBy( $mgmt  )
  {
    
    if( !isset($this->node->group_by) )
    {
      return null;
    }

    if( !isset($this->node->group_by['attribute']) )
    {
      $this->builder->dumpError( 'Grouping without attribute' );
      return null;
    }

    $attrKey = trim($this->node->group_by['attribute']);
    
    $attribute = $mgmt->entity->getAttribute($attrKey);
    
    if( !$attribute )
    {
      $this->builder->dumpError( 'Requested nonexisting grouping attribute '.$attrKey );
      return null;
    }
    
    return $attribute;

  }//end public function getGroupBy */
 
  /**
   * @return array<LibGenfTreeNodeFieldOrder>
   *
   */
  public function getGroupByFields(  )
  {

    $fields = array();
    if( isset( $this->node->group_by->field ) )
    {
      foreach( $this->node->group_by->field as $field )
      {
        $fields[] = new LibGenfTreeNodeUiListField( $field );
      }
      return $fields;
    }

    return null;

  }//end public function getGroupBy */
  
  /**
   * @return boolean 
   */
  public function isGrouped( )
  {

    if( !isset($this->node->group_by['attribute']) )
    {
      return false;
    }

    return true;

  }//end public function isGrouped */
  
  /**
   * Wir für die Switch Contexte benötigt
   * @return null
   */
  public function getColorSource()
  {
    return null;
  }//end public function getColorSource */
  
}//end class LibGenfTreeNodeComponentSelectbox

