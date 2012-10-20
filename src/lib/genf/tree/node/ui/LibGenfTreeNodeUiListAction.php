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
class LibGenfTreeNodeUiListAction
  extends LibGenfTreeNodeTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var array<LibGenfTreeNodeCondition>
   */
  protected $conditions = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return array<LibGenfTreeNodeCondition>
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

  
}//end class LibGenfTreeNodeUiListAction */

