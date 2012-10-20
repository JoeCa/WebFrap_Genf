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
class LibGenfTreeNodeItemRoleListRole
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var array<LibGenfTreeNodeCondition>
   */
  protected $conditions = null;
  
////////////////////////////////////////////////////////////////////////////////
// Load Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = trim( $this->node['name'] );
    
    if( isset($this->node->access) )
    {
      $this->access = new LibGenfTreeNodeElementAccess($this->node->access);
    }

  }//end protected function loadChilds */
  
////////////////////////////////////////////////////////////////////////////////
// getter + setter
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return LibGenfTreeNodeElementAccess
   */
  public function getAccess()
  {
    
    return $this->access;
  }//end public function getAccess */
  
  /**
   * Prüfen ob es eine minimal Cardinalität gibt
   * @return int
   */
  public function getMin()
  {
    return isset( $this->node->constraints->cardinality['min'] )
      ? trim( $this->node->constraints->cardinality['min'] )
      : null;
      
  }//end public function getMin */
  
  /**
   * Prüfen ob es eine maximal Cardinalität gibt
   * @return int
   */
  public function getMax()
  {
    return isset( $this->node->constraints->cardinality['max'] )
      ? trim( $this->node->constraints->cardinality['max'] )
      : null;
      
  }//end public function getMax */
  

  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return [LibGenfTreeNodeCondition]
   */
  public function getConditions()
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
    
  }//end public function getConditions */
  
  
  /**
   * @param string $type
   * @return array<LibGenfTreeNodeCondition>
   */
  public function getConditionsByType( $type )
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
    
  }//end public function getConditionsByType */
  
////////////////////////////////////////////////////////////////////////////////
// Generator Getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * Den Condition Generator holen
   * @return LibGeneratorWbfCondition
   */
  public function getConditionGenerator()
  {
    return $this->builder->getGenerator( "Condition" );
  }//end public function getConditionGenerator */

}//end class LibGenfTreeNodeItemRoleList

