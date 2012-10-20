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
class LibGenfTreeNodeValue
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// methodes
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
  public function getKey()
  {
    return trim($this->node['key']);
  }//end public function getKey */
  
  /**
   * @return string
   */
  public function getValue()
  {
    return trim($this->node['value']);
  }//end public function getValue */

  /**
   * @return string
   */
  public function isNull()
  {
    return isset($this->node['null']);
  }//end public function getValue */

}//end class LibGenfTreeNodeValue

