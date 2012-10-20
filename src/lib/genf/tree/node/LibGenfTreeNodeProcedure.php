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
class LibGenfTreeNodeProcedure
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var array<LibGenfTreeNodeCondition>
   */
  protected $conditions = array();
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameProcedure( $this->node );
    $this->loadConditions();
    
  }//end protected function loadChilds */
  
  /**
   * @return void
   */
  public function loadConditions()
  {
    
    if( !isset( $this->node->conditions ) )
      return;
    
    foreach( $this->node->conditions->condition as $condition )
    {
      
      $nodeClass = 'LibGenfTreeNodeCondition'
        .SParserString::subToCamelCase( trim($condition['type']) );
      
      $this->conditions[] = new $nodeClass( $condition );
    }
    
  }//end public function loadConditions */
  
  /**
   * @return array<LibGenfTreeNodeCondition>
   */
  public function getConditions()
  {
    
    return $this->conditions;
    
  }//end public function getConditions */

  /**
   * @return string
   */
  public function getType()
  {

    if(!isset($this->node['type']))
      return null;
    else
      return trim($this->node['type']);

  }//end public function getType */
  
  /**
   * @return string
   */
  public function getTypeClass()
  {

    if(!isset($this->node['type']))
      return null;
    else
      return SParserString::subToCamelCase( trim( $this->node['type'] ) ) ;

  }//end public function getTypeClass */

}//end class LibGenfTreeNodeAction

