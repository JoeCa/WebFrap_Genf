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
  <check type="path" name="archived_projects" >
  
    <controls>
      
      <control location="sub_panel" type="check_button"  > 
      
        <label>
          <text lang="de" >Archive</text>
          <text lang="en" >Archive</text>
        </label>

        <default></default>
        
      </control>
    
    </controls>
    
    <code>or project_task.id_develop_status.access_key IN( "archived" )</code>
    
  </check>
 * 
 */
class LibGenfTreeNodeFilterCheck
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Liste mit den operatoren
   * @var array
   */
  protected $operatorMap = array
  (
    'bigger'   => '>',
    'max'      => '<=',
    'smaller'  => '<',
    'equals'   => '=',
    'min'       => '>=',
    'like'       => 'like',
    'same'       => 'ilike',
    'start_with' => 'like',
    'end_with'   => 'like',
    'contains'   => 'like',
  
    '>'        => '>',
    '<='       => '<=',
    '<'        => '<',
    '='        => '=',
    '>='       => '>=',
    'like'     => 'like',
    'ilike'    => 'ilike',
  );

/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    if( isset( $this->node['name'] ) )
      $this->name = new LibGenfNameMin( trim($this->node['name']) );

  }//end protected function loadChilds */
  
/*//////////////////////////////////////////////////////////////////////////////
// getter methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string
   */
  public function getName()
  {

    return trim( $this->node['name'] ) ;

  }//end public function getName */
  
  /**
   * @return string
   */
  public function getNameKey()
  {

    return SParserString::subToCamelCase( trim( $this->node['name'] ) );

  }//end public function getNameKey */
  
  /**
   * @return string
   */
  public function getBlock()
  {

    return isset($this->node['block'])
      ? trim( $this->node['block'] )
      : null;

  }//end public function getBlock */
  
  /**
   * @return string
   */
  public function getBlockKey()
  {

    return isset($this->node['block'])
      ? SParserString::subToCamelCase(trim( $this->node['block'] ))
      : null;

  }//end public function getBlockKey */
  
  /**
   * @return string
   */
  public function getType()
  {

    if( isset($this->node['type']) )
      return strtolower( trim( $this->node['type'] ) ) ;
    else
      return 'path';

  }//end public function getType */
  
  /**
   * @return string
   */
  public function isType( $type )
  {

    if( isset( $this->node['type'] ) )
      return strtolower( $type ) == strtolower( trim( $this->node['type'] ) ) ;
    else
      return strtolower( $type ) == 'path';

  }//end public function isType */

  /**
   * @return string
   */
  public function getMaxLevel()
  {

    if( isset($this->node['max_level']) )
      return strtoupper(trim( $this->node['max_level'] ));
    else
      return 'ADMIN';

  }//end public function getMaxLevel */


  /**
   * @return string
   */
  public function getFieldName()
  {

    ///TODO error handling
    if( isset($this->node['field']) )
      return trim( $this->node['field'] );
    else 
      return 'rowid';
      
  }//end public function getFieldName */
  
  /**
   * @return string
   */
  public function getRefFieldName()
  {

    ///TODO error handling
    if( isset($this->node['ref_field']) )
      return trim( $this->node['ref_field'] );
    else 
      return 'access_key';
      
  }//end public function getRefFieldName */
  
  /**
   * @return string
   */
  public function getRefType()
  {

    if( isset($this->node['ref_type']) )
      return trim( $this->node['ref_type'] );
    else 
      return null;
      
  }//end public function getRefType */
  
  /**
   * @return boolean
   */
  public function isNullValue()
  {
    
    if( !isset($this->node->value) )
      return true;
    
    return false;

  }//end public function isNullValue */
  
  /**
   * @return string
   */
  public function getValue()
  {
    
    if( !isset($this->node->value) )
      return null;
    
    return trim( $this->node->value );

  }//end public function getValue */
  
  /**
   * Über den Value Type kann definiert werden ob der Wert Hardcodiert
   * oder der name eines Parameters / einer Variablen ist
   * @return string
   */
  public function getValueType()
  {
    
    if( !isset($this->node->value['type']) )
      return null;
    
    return trim( $this->node->value['type'] );

  }//end public function getValueType */
  
  /**
   * @return string
   */
  public function getOperator()
  {
    
    if( !isset($this->node->value['operator']) )
      return '=';
    
    $operator = strtolower(trim( $this->node->value['operator'] ));
    
    if( isset( $this->operatorMap[$operator] ) )
    {
      return $this->operatorMap[$operator];
    }
    else
    {
      $this->builder->error( "Got invalid Operator {$operator} in a filter of ".$this->builder->dumpEnv() );
      return null;
    }
      

  }//end public function getOperator */
  
  /**
   * Prüfen ob der Filter negiert werden soll
   * @return boolean
   */
  public function not()
  {
    
    if( isset($this->node->not) )
      return true;
    
    return false;

  }//end public function not */
  
  
  /**
   * @return string
   */
  public function getClass()
  {

    ///TODO error handling
    if( isset( $this->node['class'] ) )
      return SParserString::subToCamelCase( trim( $this->node['class'] ) );
    else 
      return null;
      
  }//end public function getClass */
  
  /**
   * prüfen ob ein pfad existiert
   * @return boolean
   */
  public function hasPath()
  {

    return isset( $this->node->path );

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
   * @param LibGenfTreeNodeEntity $entity
   * @return LibGenfNameManagement
   */
  public function getFieldTarget( $entity, $type = true )
  {

    return $entity->getAttrTarget( $this->getFieldName(), $type );

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

    return strtoupper(trim($this->set['access_level']));

  }//end public function getNewAccesLevel */

  /**
   * @return string
   */
  public function getNewRefLevel()
  {

    return strtoupper(trim($this->set['ref_level']));

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

    return trim( $this->node->code );

  }//end public function getCode */
  
  /**
   * @deprecated in dieser form veraltet
   * @return string
   */
  public function getFilterName()
  {

    return SParserString::subToCamelCase( trim( $this->node ) ) ;

  }//end public function getFilterName */
  
  
  /**
   * Der Visibilität des Buttons kann auf bestimmte Profile beschränkt werden
   * 
   * @return array
   */
  public function getProfiles()
  {
    
    if( !isset( $this->node->profiles->profile ) )
      return array();
      
    $profiles = array();
    
    foreach( $this->node->profiles->profile as $profile )
    {
      $profiles[trim($profile['name'])] = trim($profile['name']);
    }
    
    return $profiles;
    
  }//end public function getProfiles */
  
////////////////////////////////////////////////////////////////////////////////
// Controls
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return boolean
   */
  public function hasControls()
  {
    
    return isset( $this->node->controls->control );
    
  }//end public function hasControls */
  
  /**
   * @return boolean
   */
  public function isControllAble()
  {
    
    if( isset( $this->node->controls->control ) )
      return true;
      
     if( isset( $this->node->control_able ) )
      return true;
    
    return false;
    
  }//end public function isControllAble */
  
  /**
   * @param string $location
   * @return LibGenfTreeNodeUiControl
   */
  public function getControl( $location = 'sub_panel' )
  {

    if( !isset( $this->node->controls->control ) )
      return null;
      
    $control = $this->node->controls->xpath( './control[@location="'.$location.'"]' );
    
    if( !$control )
      return null;
      
    return new LibGenfTreeNodeUiControl( $control[0] );

  }//end public function getControl */

  /**
   * 
   */
  public function defActive()
  {
    
    if( !isset( $this->node->active ) )
      return false;
      
    if( 'false' == trim($this->node->active) )
    {
      return false;
    }
    else 
    {
      return true;
    }
    
  }//end public function defActive */
  
  
////////////////////////////////////////////////////////////////////////////////
// Subfilter Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $type
   * @param boolean $withControls Nur Filter zurückgeben welche auch Controll Elemente beschreiben
   * @return array<LibGenfTreeNodeFilterCheck>
   */
  public function getSubFilter( )
  {

    if( !isset( $this->node->subchecks->check ) )
    {
      return array();
    }
    
    $filter = $this->node->subchecks;

    $checks = array();
    
    foreach( $filter->check as $check  )
    {
      
      $type = SParserString::subToCamelCase( trim($check['type']) );
      
      $className = 'LibGenfTreeNodeFilter_'.$type;
      
      if( !Webfrap::classLoadable( $className ) )
      {
        $this->builder->dumpError( "Got invalid subcheck type ".$type );
        continue;
      }
      
      
      $checks[] = new $className( $check );
        
    }
    
    return $checks;

  }//end public function getSubFilter */
  
  
  /**
   * Prüfen ob filter vorhanden sind
   * 
   * @param string $type
   * @param boolean $withControls Nur Filter zurückgeben welche auch Controll Elemente beschreiben
   * @return string
   */
  public function hasSubFilter( )
  {

    return isset( $this->node->subchecks->check );

  }//end public function hasSubFilter */
  

}//end class LibGenfTreeNodeFilterCheck

