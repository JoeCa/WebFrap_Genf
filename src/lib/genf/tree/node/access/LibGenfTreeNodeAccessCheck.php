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
class LibGenfTreeNodeAccessCheck
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @return string
   */
  public function getType()
  {

    if( isset($this->node['type']) )
      return strtolower( trim( $this->node['type'] ) ) ;
    else
      return 'bdl';

  }//end public function getType */

  /**
   * @return string
   */
  public function getMaxLevel()
  {

    if( isset($this->node['max_level']) )
      return strtoupper(trim( $this->node['max_level'] ));
    else
      return null;

  }//end public function getMaxLevel */
  
  /**
   * @return string
   */
  public function targetMaxLevel()
  {
    
    if( !isset($this->node->level['max']) )
      return null;
      
    return trim($this->node->level['max']);
    
  }//end public function targetMaxLevel */
  
  /**
   * @return string
   */
  public function targetMinLevel()
  {
    
    if( ! isset($this->node->level['min']) )
      return null;
      
    return trim($this->node->level['min']);
    
  }//end public function targetMinLevel */


  /**
   * Fieldname
   * 
   * kann leer sein wenn role somewhere der type ist
   * @return string
   */
  public function getFieldName()
  {

    if( isset( $this->node['field'] ) )
      return trim( $this->node['field'] );
    else if( isset( $this->node['attr'] ) )
      return trim( $this->node['attr'] );
    else
      return null;

  }//end public function getFieldName */
  
  /**
   * Fieldname
   * 
   * kann leer sein wenn role somewhere der type ist
   * @return string
   */
  public function getRefFieldName()
  {

    if( isset( $this->node['ref_field'] ) )
      return trim( $this->node['ref_field'] );
    else if( isset( $this->node['ref_attr'] ) )
      return trim( $this->node['ref_attr'] );
    else
      return null;

  }//end public function getFieldName */
  
  /**
   * Erfragen zu was der Check in Relation steht,
   * Möglich sind Dataset, Area, Entity oder Global
   * Teils werden nur bestimmte relationen behandelt, es gibt dann einen fallback
   * auf das erste größere oder erst kleinere
   * 
   * @return string
   */
  public function getRelation()
  {

    return isset( $this->node['relation'] )
      ? strtolower( trim($this->node['relation']) )
      : 'dataset';

  }//end public function getRelation */
  
  /**
   * prüfen ob ein pfad existiert
   * @return boolean
   */
  public function hasPath()
  {
    
    return isset($this->node->path);
    
    /*
    if( !isset($this->node->path) )
      return false;

    $path = trim( $this->node->path );
    
    $tmp = explode( ':', $path );
    
    if( count( $tmp ) > 1 )
      return true;
    else
      return false;
    */

  }//end public function hasPath */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return array<string,Entity>
   */
  public function getPath( $management )
  {

    if( isset( $this->node->path ) )
    {
      return $this->buildEntityPath
      ( 
        $management->entity, 
        explode( '.', trim( $this->node->path ) ) 
      );
    }
    else
    {
      return null;
    }

  }//end public function getPath */


  /**
   * @return string
   */
  public function getPathStart()
  {

    $tmp = explode( '.', trim( $this->node->path ) );

    return $tmp[0];

  }//end public function getPathStart */
  
  /**
   * @return array
   */
  public function getSinglePath()
  {

    $tmp = explode( '.', trim( $this->node->path ) );

    return $tmp;

  }//end public function getSinglePath */

  /**
   * @param LibGenfTreeNodeEntity $entity
   * @param string $type
   * @return LibGenfNameManagement
   */
  public function getFieldTarget( $entity, $type = true )
  {
    
    $fieldName    = $this->getFieldName();
    
    if( '' == trim($fieldName)  )
    {
      $this->builder->error( 'Missing Fieldname in AccessCheck Node '.$this->debugData().' '.$this->builder->dumpEnv() );
      return null;
    }
    
    if( isset( $this->node['management'] ) )
    {
      $management = $this->builder->getManagement( trim($this->node['management']) );
      
      if( !$management )
        return null;
        
      if( $type === 'management' )
      {
        return $management; 
      }
      else if( $type === 'entity' )
      {
        return $management->entity; 
      }
      else 
      {
        return $management->name;
      }
      
    }
    else 
    {
      return $entity->getAttrTarget( $fieldName, $type  );
      
    }

  }//end public function getFieldTarget */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfNameManagement
   */
  public function getRefFieldTarget( $management  )
  {

    $refFieldName = $this->getRefFieldName();
    
    if( '' == trim($refFieldName)  )
    {
      $this->builder->error( 'Missing Fieldname in AccessCheck Node '.$this->debugData().' '.$this->builder->dumpEnv() );
      return null;
    }
    
    $attr = $management->getField( $refFieldName );
    
    return $attr->attribute->targetManagement();

  }//end public function getFieldTarget */

  /**
   * @return string
   */
  public function getRoles()
  {

    $roles = array();

    if( !isset($this->node->roles->role) )
      return $roles;

    foreach( $this->node->roles->role as $role )
    {
      $roles[] = trim( $role['name'] );
    }

    return $roles;

  }//end public function getRoles */

  /**
   * @return string
   */
  public function getNewAccesLevel()
  {

    return isset($this->set['access_level'])
      ? strtoupper(trim($this->set['access_level']))
      : null;

  }//end public function getNewAccesLevel */

  /**
   * @return string
   */
  public function getNewRefLevel()
  {

    return isset($this->set['ref_level'])
      ? strtoupper(trim($this->set['ref_level']))
      : null;

  }//end public function getNewRefLevel */

  /**
   * @return string
   * @return array<string role:string level.upper()>
   */
  public function getNewRefLevels()
  {

    $levels = array();

    if( !isset($this->node->set->level) )
      return $levels;

    foreach( $this->node->set->level as $level )
    {
      $levels[trim($level['name'])] = strtoupper(trim( $level['level'] ));
    }

    return $levels;

  }//end public function getNewRefLevels */
  
  /**
   * 
   * Gibt eine Liste mit Rollenzugehörigkeiten zurück
   *  
   * @return array<string rolename: string rolename>
   */
  public function getNewRoles()
  {

    $roles = array();

    if( !isset($this->node->set->roles->role) )
      return $roles;

    foreach( $this->node->set->roles->role as $role )
    {
      $roles[trim($role['name'])] = trim( $role['name'] );
    }

    return $roles;

  }//end public function getNewRoles */

  /**
   * @return string
   */
  public function getCode()
  {

    return trim( $this->node );

  }//end public function getCode */
  
////////////////////////////////////////////////////////////////////////////////
// 
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return boolean
   */
  public function isReadOnly()
  {
    
    if( !isset( $this->node->set->readonly ) )
    {
      return null;
    }
    
    if
    (
      isset( $this->node->set->readonly['state'] ) 
        && 'false' == trim($this->node->set->readonly['state']) 
    )
      return false;
      
    return true;
      
    
  }//end public function isReadOnly */
  
  /**
   * @return boolean
   */
  public function isRequired()
  {
    
    if( !isset( $this->node->set->required ) )
    {
      return null;
    }
    
    if
    (
      isset( $this->node->set->required['state'] ) 
        && 'false' == trim($this->node->set->required['state']) 
    )
      return false;
      
    return true;
    
  }//end public function isRequired */

}//end class LibGenfTreeNodeAccessCheck

