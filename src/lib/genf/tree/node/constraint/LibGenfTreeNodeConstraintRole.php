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
 * <role 
 *   name=""
 *   amount=""
 *  min=""
 *  max="" />
 * 
 */
class LibGenfTreeNodeConstraintRole
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Name der Rolle
   * @var string
   */
  public $name = null;
  
  /**
   * Code Key der Rolle
   * @var string
   */
  public $codeKey = null;
  
  /**
   * Label der Rolle
   * @var string
   */
  public $label = null;
  
  /**
   * Genaue der Rollen
   * @var int
   */
  public $amount = null;
  
  /**
   * Minimal nötige Anzahl Rollen
   * @var int
   */
  public $min = null;
  
  /**
   * Maximal nötige Anzahl Rollen
   * @var int
   */
  public $max = null;
  

  /**
   * @overwrite
   */
  protected function loadChilds()
  {
    
    $this->name = trim( $this->node['name'] );
    $this->label = SParserString::subToName( $this->name, false );
    $this->codeKey = SParserString::subToCamelCase( $this->name );
    
    if( isset( $this->node['amount'] ) )
      $this->amount = (int)trim( $this->node['amount']);
      
    if( isset( $this->node['min'] ) )
      $this->min = (int)trim( $this->node['min'] );
      
    if( isset( $this->node['max'] ) )
      $this->max = (int)trim( $this->node['max'] );
    
  }//end protected function loadChilds */

}//end class LibGenfTreeNodeConstraintRole

