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
class LibGenfTreeNodeUiListSearchCol
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Flag ob nach mehr als nur einen Suchfeld gesucht werden soll.
   * @var boolean
   */
  public $isMulti = null;
  
  /**
   * Liste mit allen Suchattributen
   * @var array
   */
  public $attributes = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getRefTarget( )
  {
    // if not exists, that's an error
    if( !isset( $this->node['target'] ) )
      return null;

    return trim( $this->node['target'] );

  }//end public function getRefTarget */

  /**
   * @return string
   */
  public function getAttrName( )
  {
    // if not exists, that's an error
    if( !isset( $this->node['search_attr'] ) )
      return null;

    return trim( $this->node['search_attr'] );

  }//end public function getAttrName */
  
  /**
   * Prüfen ob nach mehr als nur einem Attribut gesucht werden soll
   * @return boolean
   */
  public function hasMultipleAttributes( )
  {
    
    if( !is_null( $this->isMulti ) )
      return $this->isMulti;
    
    // if not exists, that's an error
    if( !isset( $this->node['search_fields'] ) )
    {
      $this->isMulti = false;
      return false;
    }

    $this->isMulti = true;
    return true;

  }//end public function hasMultipleAttributes */
  
  /**
   * @param LibGenfEnvManagement $env
   * @param LibGenfTreeNodeReference $ref
   * @return stdClass
   */
  public function getAttrKey( $env, $ref = null )
  {

    $context = new stdClass;

    if( !isset( $this->node['search_key'] ) )
    {
      if( isset( $this->node['search_fields'] ) )
      {
        
        $attrs           = $this->getSearchAttributes( $env, $ref );
        
        if( isset($attrs[0]) && isset($attrs[0]->attribute)  )
        {
          $searchField     = $attrs[0];
          $context->env    = $searchField->contextKeyName;
          $context->field  = $searchField->attribute->name->name;
        }
        else 
        {
          Log::warn( "Did not get Search Fields ". $this->node->asXML().' '.$env->debugData() );
        }
      }
      else 
      {

        $searchField     = $this->getSearchAttribute( $env, $ref );
        
        if( $searchField  )
        {
          $context->env    = $searchField->contextKeyName;
          $context->field  = $searchField->attribute->name->name;
        }
        else 
        {
          Log::warn( "Did not get Search Fields ". $this->node->asXML().' '.$env->debugData() );
        }
      }
      
    }
    else 
    {
      $val = strtolower(trim($this->node['search_key']));
      $tmp = explode( '.', $val );

      $context->env   = $tmp[0];
      $context->field = $tmp[1];
    }
    
    return $context;

  }//end public function getAttrKey */
  
  /**
   *
   * @return string
   */
  public function getRefAttrName( )
  {
    // optional
    if( !isset( $this->node['search_ref_attr'] ) )
      return null;

    return trim($this->node['search_ref_attr']);

  }//public function getRefAttrName */
  
  /**
   * Wird bei between benötigt
   * @return string
   */
  public function getAttrNameEnd( )
  {
    // if not exists, that's an error
    if( !isset( $this->node['search_attr_end'] ) )
      return null;

    return trim( $this->node['search_attr_end'] );

  }//end public function getAttrNameEnd */
  
  /**
   * Wird bei between benötigt
   * @return string
   */
  public function getRefAttrNameEnd( )
  {
    // optional
    if( !isset( $this->node['search_ref_attr_end'] ) )
      return null;

    return trim($this->node['search_ref_attr_end']);

  }//public function getRefAttrNameEnd */
  
  /**
   *
   * @return string
   */
  public function getSearchType( )
  {
    // optional
    if( !isset( $this->node['search'] ) )
      return 'equals';

    return trim($this->node['search']);

  }//public function getSearchType */
  
  /**
   * @return string
   */
  public function getSearchTargetType( )
  {
    // optional
    if( !isset( $this->node['search_target_type'] ) )
    {
      if( 'ref' == trim($this->node['type'])   )
      {
        return 'ref';
      }
      else if( 'role' == trim($this->node['type'])   )
      {
        return 'role';
      }
      else if( isset( $this->node['search_roles'] )   )
      {
        return 'role';
      }
      return 'field';
    }
      
    return trim($this->node['search_target_type']);

  }//public function getSearchTargetType */
  
  /**
   *
   * @return string
   */
  public function getSesitivity( )
  {
    // optional
    if( !isset( $this->node['search_sensitivity'] ) )
      return 'strict';

    return trim($this->node['search_sensitivity']);

  }//public function getSearchType */
  
  /**
   * Prüfen ob die Suche negiert werden soll, also nur dinge anzeigen
   * die nicht den kriterien entsprechen
   * @return string
   */
  public function getNot( )
  {
    // optional
    if( !isset( $this->node['search_not'] ) )
      return false;

    return true;

  }//public function getNot */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @param LibGenfTreeNodeReference $ref
   * @return TContextAttribute
   */
  public function getSearchAttribute( $env, $ref = null )
  {
    
    $fieldName     = $this->getAttrName();
    $refFieldName  = $this->getRefAttrName();
    $src           = $this->getReference();
    $refType       = $this->getRefType();
    
    $cntMgmt = $env->getContextMgmt();
    
    if( !$ref )
    {
      $field = $cntMgmt->getField( $fieldName, $src, $refType );
      
      if( !$field )
      {
        $this->builder->warn( "Requested nonexisting field $fieldName, $src, $refType ".__METHOD__ );
      }
      
    }
    else 
    {
      $refTNode = $ref->targetManagement();
      $field    = $refTNode->getField( $fieldName, $src, $refType );
      
      if( !$field )
      {
        $this->builder->warn( "Requested nonexisting ref field $fieldName, $src, $refType, {$refTNode->name->name} ".__METHOD__ );
      }
    }
    

    if( !$refFieldName )
    {
      $field->contextKeyName = $cntMgmt->name->name;
      $field->entityName = $cntMgmt->name->source;
      return $field;
    }
      
    $targetMgmt = $field->attribute->targetManagement();
      
    /* @var $targetMgmt LibGenfTreeNodeManagement */
    $refField = $targetMgmt->getField( $refFieldName );
    
    if( !$refField )
    {
      $this->builder->warn( "Requested nonexisting field ".$refFieldName." from target ".$targetMgmt->name->name );
    }
    
    if( $src )
    {
      $refField->contextKeyName = $src;
      $refField->entityName     = $targetMgmt->name->source;
    }
    else
    { 
      $refField->contextKeyName = $targetMgmt->name->name;
      $refField->entityName     = $targetMgmt->name->source;
    }
    
    
    return $refField;

  }//end public function getSearchAttribute */
  
  /**
   * @param LibGenfEnvManagement $env
   * @param LibGenfTreeNodeReference $ref
   * @return TContextAttribute
   */
  public function getSearchAttributes( $env, $ref = null )
  {
    
    if( !is_null( $this->attributes ) )
      return $this->attributes;
    
    $searchFields    = array();
    $searchFieldData = $this->parseFieldAddress();
    
    $cntMgmt = $env->getContextMgmt();
    

    foreach( $searchFieldData as $fieldData )
    {
      
      if( !$ref )
      {
        $field = $cntMgmt->getField( $fieldData->attr, $fieldData->src, $fieldData->ref_type );
      }
      else 
      {
        $refTNode = $ref->targetManagement();
        $field = $refTNode->getField( $fieldData->attr, $fieldData->src, $fieldData->ref_type );
      }
      
      if( !$field )
      {
        Debug::console( "Missing field $fieldData->attr, $fieldData->src, $fieldData->ref_type " );
        continue;
      }
      
      
      if( !$fieldData->ref_attr )
      {
        if( $ref )
        {
          $refTMgmt = $ref->targetManagement();
          $field->contextKeyName = $refTMgmt->name->name;
          $field->entityName     = $refTMgmt->name->source;
        }
        else 
        {
          $field->contextKeyName = $cntMgmt->name->name;
          $field->entityName     = $cntMgmt->name->source;
        }
        
        $searchFields[]        = $field;
      }
      else 
      {
        
        
        $targetMgmt = $field->attribute->targetManagement();
      
        /* @var $targetMgmt LibGenfTreeNodeManagement */
        $refField = $targetMgmt->getField( $fieldData->ref_attr );
        
        if( !$refField )
        {
          $this->builder->warn( "Requested nonexistint ref_attr {$fieldData->ref_attr} from {$targetMgmt->name->name} " );
          continue;
        }
        
        if( $fieldData->src )
        {
          $refField->contextKeyName = $fieldData->src;
          $refField->entityName     = $targetMgmt->name->source;
        }
        else
        { 
          $refField->contextKeyName = $targetMgmt->name->name;
          $refField->entityName     = $targetMgmt->name->source;
        }
        
        $searchFields[] = $refField;
        
      }
      
    }
    
    $this->attributes = $searchFields;

    if( !count($searchFields) )
    {
      $this->builder->warn( "Found NO Search Fields!!! ".$env->debugData() );
    }

    return $searchFields;

  }//end public function getSearchAttribute */
  
  /**
   * Wird bei between benötigt, das normale Attribute wird automatisch zum 
   * Start Attribute, das End Attribute markiert somit das Ende
   * 
   * @param LibGenfEnvManagement $env
   * @return TContextAttribute
   */
  public function getSearchAttributeEnd( $env )
  {
    
    $fieldName     = $this->getAttrNameEnd();
    $refFieldName  = $this->getRefAttrNameEnd();
    $src           = $this->getReference();
    $refType       = $this->getRefType();
    
    
    $cntMgmt = $env->getContextMgmt();
    
    $field = $cntMgmt->getField( $fieldName, $src, $refType );
    
    if( !$refFieldName )
    {
      $field->contextKeyName = $cntMgmt->name->name;
      $field->entityName     = $cntMgmt->name->source;
      return $field;
    }
      
    $targetMgmt = $field->attribute->targetManagement();
      
    $refField = $targetMgmt->getField( $refFieldName );
    
    if( $src )
    {
      $refField->contextKeyName = $src;
      $refField->entityName     = $targetMgmt->name->source;
    }
    else
    { 
      $refField->contextKeyName = $targetMgmt->name->name;
      $refField->entityName     = $targetMgmt->name->source;
    }
    
    
    return $refField;

  }//end public function getSearchAttributeEnd */
  
  /**
   * @param LibGenfEnvManagement $env
   * @return TContextAttribute
   */
  public function getAttribute( $env )
  {
    
    $fieldName = $this->getAttrName();
    $src       = $this->getRefAttrName();
    $refType   = $this->getRefType();
    
    $cntMgmt = $env->getContextMgmt();
    
    return $cntMgmt->getField( $fieldName, $src, $refType );

  }//end public function getAttribute */


  /**
   * @return string
   */
  public function getReference( )
  {

    if( !isset( $this->node['search_ref'] ) )
      return null;

    return trim( $this->node['search_ref'] );

  }//public function getReference */

  /**
   * Definieren ob auf der connection oder target tabelle gesucht werden soll
   * @return string
   */
  public function getRefType( )
  {
    // optional
    if( !isset( $this->node['search_ref_type'] ) )
      return null;

    return trim($this->node['search_ref_type']);

  }//public function getRefType */
  
////////////////////////////////////////////////////////////////////////////////
// role methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param $env LibGenfEnv
   * @return TArray
   */
  public function getRolesKey( $env )
  {
    
    $keys = new TArray();
    
    $keys->key     = 'Role';
    $keys->urlKey  = 'roles';
    
    $cntMgmt = $env->getContextMgmt();
    
    $keys->env     = $env->management->name->name;
    
    return $keys;
    
  }//end public function getRolesKey */
  
  /**
   * Liste der Rollen welche bei der Suche eingebunden werden sollen
   * @return array
   */
  public function getRoles()
  {
    
    if( !isset( $this->node['search_roles'] ) )
      return array();

    return explode( ';', trim($this->node['search_roles']) );
    
  }//end public function getRoles */
  
////////////////////////////////////////////////////////////////////////////////
// parser methoden
////////////////////////////////////////////////////////////////////////////////

  /**
   * Parsen der des Adresstrings wenn die neue notation für die suche verwendet wird
   * @return array[stdClass{ref_type,src,attr,ref_attr}]
   */
  public function parseFieldAddress( )
  {
    // optional
    if( !isset( $this->node['search_fields'] ) )
      return array();

    $fields = explode( ';', trim($this->node['search_fields']) );

    $parsedFields = array();
    
    foreach( $fields as $fieldData )
    {
      
      $parsedField = new stdClass();
      
      // check auf den RefType
      $tmp = explode( '/', $fieldData );
      
      if( isset($tmp[1]) )
      {
        $parsedField->ref_type = $tmp[0];
        $fieldData = $tmp[1];
      }
      else 
      {
        $parsedField->ref_type = null;
        $fieldData = $tmp[0];
      }
      
      // check auf die source
      $tmp = explode( ':', $fieldData );
      
      if( isset($tmp[1]) )
      {
        $parsedField->src  = $tmp[0];
        $fieldData         = $tmp[1];
      }
      else 
      {
        $parsedField->src  = null;
        $fieldData         = $tmp[0];
      }
      
      // check auf die fields
      $tmp = explode( '.', $fieldData );
      
      if( isset($tmp[1]) )
      {
        $parsedField->attr     = $tmp[0];
        $parsedField->ref_attr = $tmp[1];
      }
      else 
      {
        $parsedField->attr      = $tmp[0];
        $parsedField->ref_attr  = null;
      }
      
      $parsedFields[] = $parsedField;
      
    }// end foreach
    
    return $parsedFields;

  }//public function parseFieldAddress */
  
  
  
  /**
   * @return array
   */
  public function getDebugDump()
  {
    
    
    $name = '';
    
    if( isset( $this->node['search_fields'] ) )
    {
      $name .= 'search_fields: '.trim($this->node['search_fields']);
    }
    elseif( isset( $this->node['search_attr'] )  )
    {
      $name .= 'search_attr: '.trim($this->node['search_attr']);
    }
    else
    {
      $name .= 'Invalid Search Col!';
    }

    
    return array
    (
      'node type: '.get_class($this),
      'name : '.$name,
    );

  }//end public function getDebugDump */

}//end class LibGenfTreeNodeUiListSearchCol

