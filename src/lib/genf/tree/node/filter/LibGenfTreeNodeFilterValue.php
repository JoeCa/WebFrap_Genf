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
class LibGenfTreeNodeFilterValue
  extends LibGenfTreeNodeFilterCheck
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  
  public $type = 'value';
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////


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
   * @return string
   */
  public function getOperator()
  {
    
    if( !isset($this->node->value['operator']) )
      return '=';
    
    $operator = strtolower( trim( $this->node->value['operator'] ) );
    
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
  
}//end class LibGenfTreeNodeFilterValue

