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
class LibGenfTreeNodeConstraint
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * 
   * @var array
   */
  public $conditions = array();

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfNameMin
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */

  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameMin( $this->node );
    $this->loadConditions();

  }//end protected function loadChilds */
  
  /**
   * @return void
   */
  public function loadConditions()
  {
    
    if( !isset( $this->node->conditions->condition ) )
      return;

    foreach( $this->node->conditions->condition as $condition )
    {
      
      $nodeClass = 'LibGenfTreeNodeCondition'
        .SParserString::subToCamelCase( trim($condition['type']) );
      
      $this->conditions[] = new $nodeClass( $condition );
    }
    
  }//end public function loadConditions */
  
  /**
   * @return array[LibGenfTreeNodeCondition]
   */
  public function getConditions()
  {
    
    return $this->conditions;
    
  }//end public function getConditions */


}//end class LibGenfTreeNodeConstraint

