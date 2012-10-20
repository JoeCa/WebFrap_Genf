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
 * @example Tag Beispiel
 * <condition type="reference" >
 * 
 *   <fields>
 *     <field type="responsible" name="responsible" />
 *   </fields>
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeConditionReference
  extends LibGenfTreeNodeCondition
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Der Key welcher Benötigt wird um einen passenden Generator für diese
   * Condition zu laden
   * @var string
   */
  public $generatorKey = 'Reference';
  
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
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @overwrite
   */
  protected function loadChilds()
  {
    
    $this->name = trim( $this->node['name'] );
    
    if( isset( $this->node['amount'] ) )
      $this->amount = (int)trim( $this->node['amount']);
      
    if( isset( $this->node['min'] ) )
      $this->min = (int)trim( $this->node['min'] );
      
    if( isset( $this->node['max'] ) )
      $this->max = (int)trim( $this->node['max'] );

  }//end protected function loadChilds */
  
  /**
   * @param $env LibGenfEnvManagement
   */
  public function getReference( $env )
  {
    
    return $env->management->getReference( $this->name );

  }//end public function getReference */


}//end class LibGenfTreeNodeConditionReference

