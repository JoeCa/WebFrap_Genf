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
 */
class LibGenfTreeNodeCondition
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Der Key welcher Benötigt wird um einen passenden Generator für diese
   * Condition zu laden
   * @var string
   */
  public $generatorKey = null;
  
  /**
   * Subconditions
   * @var [LibGenfTreeNodeCondition]
   */
  protected $conditions = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return string
   */
  public function getTypeClass()
  {
    return $this->generatorKey;
  }//end public function getTypeClass */
  
  /**
   * @return string
   */
  public function isNegativ()
  {
    
    return isset( $this->node->not );

  }//end public function isNegativ */
  
  /**
   * @return string
   */
  public function isBreaker()
  {
    
    return isset( $this->node->break );

  }//end public function isBreaker */
  
  /**
   * @return string
   */
  public function isType( $type )
  {
    
    return strtolower(trim($this->node['type'])) == strtolower(trim($type))
      ? true
      : false;

  }//end public function isType */
  
  
  /**
   * @return string
   */
  public function getKey()
  {
    
    return isset( $this->node->attribute['key'] )
      ? trim( $this->node->attribute['key'] )
      : null;

  }//end public function getKey */
  
  /**
   * @param LibGenfTreeNodeManagement $env
   */
  public function getEnvFields( $env )
  {
    
    return array();
    
  }//end public function getEnvFields */
  
  /**
   * @param LibGenfTreeNodeManagement $env
   * @param TTabJoin $tables
   */
  public function getEnvJoins( $env, $tables )
  {

    
  }//end public function getEnvJoins */
  
  /**
   * @param LibGenfTreeNodeManagement $env
   */
  public function getEnvJoinIndex( $env )
  {
    
    return array();
    
  }//end public function getEnvJoinIndex */
  

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return [LibGenfTreeNodeCondition]
   */
  public function getSubConditions()
  {
    
    if( !is_null($this->conditions) )
      return $this->conditions;
      
    $this->conditions = array();
    
    if( !isset( $this->node->conditions->condition ) )
      return;

    foreach( $this->node->conditions->condition as $condition )
    {
      
      $nodeClass = 'LibGenfTreeNodeCondition'
        .SParserString::subToCamelCase( trim($condition['type']) );
        
      if( !Webfrap::classLoadable( $nodeClass ) )
      {
        $this->builder->dumpError( "Requested nonexisting Conditiontype {$nodeClass}" );
        continue;
      }
      
      $this->conditions[] = new $nodeClass( $condition );
    }
    
    return $this->conditions;
    
  }//end public function getSubConditions */
  
  
  /**
   * @param string $type
   * @return [LibGenfTreeNodeCondition]
   */
  public function getSubConditionsByType( $type )
  {
    
    $allConditions = $this->getConditions();
    
    if( !$allConditions )
      return array();

    $filtered = array();

    foreach( $allConditions as $condition )
    {

      if( $condition->isType( $type ) )
        $filtered[] = $condition;
    }
    
    return $filtered;
    
  }//end public function getSubConditionsByType */

}//end class LibGenfTreeNodeCondition

