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
 * 
 * @example Tag Beispiel
 * 
 */
class LibGenfTreeNodeConditionValueIn
  extends LibGenfTreeNodeCondition
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Der Key welcher Benötigt wird um einen passenden Generator für diese
   * Condition zu laden
   * @var string
   */
  public $generatorKey = 'ValueIn';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string
   */
  public function getAttribute()
  {
    
    return trim( $this->node->attribute['name'] );

  }//end public function getAttribute */

  
  /**
   * @return string
   */
  public function getRefFieldName()
  {
    
    if( isset($this->node->attribute['field']) )
    return trim( $this->node->attribute['field'] );

  }//end public function getRefFieldName */
  
  /**
   * @param LibGenfTreeEnvManagement $env
   * @return LibGenfTreeNodeAttribute
   */
  public function getRefField( $env )
  {
    
    if( isset($this->node->attribute['field']) )
    {
      
      $lAttrKey = trim( $this->node->attribute['name'] );
      
      /* @var $localAttr LibGenfTreeNodeAttribute */
      $localAttr = $env->management->getField( $lAttrKey);
      
      if( !$localAttr )
      {
        $this->builder->dumpError( "Requested nonexisting src attr: {$lAttrKey} in condition in value" );
        return null;
      }
      
      $refMgmt = $localAttr->targetManagement();
      
      if( !$refMgmt )
      {
        $this->builder->dumpError( "Attr: {$lAttrKey} has no reference target in condition in value" );
        return null;
      }
      
      $refAttrKey = trim( $this->node->attribute['field'] );
      $refAttr = $refMgmt->getField( $refAttrKey );

      if( !$refAttr )
      {
        $this->builder->dumpError( "Requested nonexisting ref attr: {$refAttrKey} in condition in value" );
        return null;
      }
      
      return $refAttr;
      
    }
    
    // gibts nicht
    return null;

  }//end public function getRefField */
  
  /**
   * @param LibGenfTreeEnvManagement $env
   * @return LibGenfTreeNodeManagement
   */
  public function getRefFieldMgmt( $env )
  {
    
    if( isset($this->node->attribute['field']) )
    {
      
      $lAttrKey = trim( $this->node->attribute['name'] );
      
      /* @var $localAttr LibGenfTreeNodeAttribute */
      $localAttr = $env->management->getField( $lAttrKey);
      
      if( !$localAttr )
      {
        $this->builder->dumpError( "Requested nonexisting src attr: {$lAttrKey} in condition in value" );
        return null;
      }
      
      $refMgmt = $localAttr->targetManagement();
      
      if( !$refMgmt )
      {
        $this->builder->dumpError( "Attr: {$lAttrKey} has no reference target in condition in value" );
        return null;
      }
      
      return $refMgmt;

      
    }
    
    // gibts nicht
    return null;

  }//end public function getRefFieldMgmt */
  
  /**
   * @param LibGenfEnvManagement $env
   * @return string
   */
  public function getKey( $env )
  {
    
    if( !isset( $this->node->attribute['ref'] ) )
      return $env->name->name;

    $refField = trim( $this->node->attribute['ref'] );
      
    $targetRefField = $env->management->getField( $refField );
    
    // fehler wurde bereits bei getEnvFields geworfen
    if( !$targetRefField )
    {
      return;
    }
    
    return $targetRefField->targetKey();

  }//end public function getKey */
  
  /**
   * @return string
   */
  public function getAttributeRef()
  {
    
    if( isset($this->node->attribute['ref']) )
      return trim( $this->node->attribute['ref'] );
    else 
      return null;

  }//end public function getAttributeRef */
  
  /**
   * @return array
   */
  public function getValues()
  {
    
    $values = array();
    
    foreach( $this->node->values->value as $value )
    {
      $values[] = trim($value);
    }
    
    return $values;

  }//end public function getValue */  

  
////////////////////////////////////////////////////////////////////////////////
// Getter für Env Fields
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfTreeNodeManagement $env
   */
  public function getEnvFields( $env )
  {
    
    $fields = array();
    
    $fieldName = trim( $this->node->attribute['name'] );
    
    if( isset( $this->node->attribute['ref'] ) )
    {
      
      $targetRefField = $env->management->getField( trim( $this->node->attribute['ref'] ) );
      
      if( !$targetRefField )
      {
        $this->builder->dumpError( "Requested nonexisting reference ".trim( $this->node->attribute['ref'] ) );
        return array();
      }
      
      $trgtMgmt  = $targetRefField->targetManagement( );
      $targetKey = $targetRefField->targetKey();
      if( !$trgtMgmt )
      {
        $this->builder->dumpError( "Target Management for ".trim( $this->node->attribute['ref'].' not exists' ) );
        return array();
      }
      
      $attribute = $trgtMgmt->getField( $fieldName );
      if( !$trgtMgmt )
      {
        $this->builder->dumpError
        ( 
          "Target Management {$trgtMgmt->name->name} for  ".trim($this->node->attribute['ref']).' has no attribute '.$fieldName 
        );
        return array();
      }
    
      $attr   = new TContextAttribute( $targetRefField, $env->management );
      $attr->fieldName = $fieldName;
    
      /*
      if( $reference )
        $attr->ref = $reference;
      */

      $attr->variante = 'def-by-condition';
      $fields[$targetKey.'_'.$fieldName] = $attr;
      
    }
    else 
    {
      
      $attribute = $env->management->getField( $fieldName );
      if( !$trgtMgmt )
      {
        $this->builder->dumpError
        ( 
          "Requested nonexisting Field  ".$fieldName 
        );
        return array();
      }

      $attr   = new TContextAttribute( $attribute, $env->management );
      $attr->variante = 'def-by-condition';
      $fields[$env->management->name.'_'.$fieldName] = $attr;
      
    }
    
    return $fields;
    
    
  }//end public function getEnvFields */
  
  /**
   * @param LibGenfTreeNodeManagement $env
   * @param TTabJoin $tables
   */
  public function getEnvJoins( $env, $tables )
  {
    
    if( !isset( $this->node->attribute['ref'] ) )
      return;
      
    $refField = trim( $this->node->attribute['ref'] );
      
    $targetRefField = $env->management->getField( $refField );
    
    // fehler wurde bereits bei getEnvFields geworfen
    if( !$targetRefField )
    {
      return;
    }
    
    $trgtMgmt  = $targetRefField->targetManagement( );
    $targetKey = $targetRefField->targetKey();
    
    // fehler wurde bereits bei getEnvFields geworfen
    if( !$trgtMgmt )
    {
      return;
    }
      
    if( isset($tables->index[$targetKey]) )
    {
      return;
    }
      
    $tables->joins[] = array
    (
      'left',                     // join
      $env->name->source,
      'rowid',
      $trgtMgmt->name->source,
      'rowid',
      null,                       // where
      $targetKey,  // alias
      'by condition '.$this->generatorKey
    );

    $tables->index[$targetKey] = array
    (
      'left',                     // join
      $env->name->source,
      'rowid',
      $trgtMgmt->name->source,
      'rowid',
      null,                       // where
      $targetKey,  // alias
      'by condition '.$this->generatorKey
    );

    
  }//end public function getEnvJoins */
  
  /**
   * @param LibGenfTreeNodeManagement $env
   */
  public function getEnvJoinIndex( $env )
  {
    
    if( !isset( $this->node->attribute['ref'] ) )
      return array();

    $refField = trim( $this->node->attribute['ref'] );
      
    $targetRefField = $env->management->getField( $refField );
    
    // fehler wurde bereits bei getEnvFields geworfen
    if( !$targetRefField )
    {
      return;
    }
    
    $targetKey = $targetRefField->targetKey();

    return array( $targetKey );
    
  }//end public function getEnvJoinIndex */

}//end class LibGenfTreeNodeConditionValueEquals

