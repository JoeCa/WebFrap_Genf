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
 * <procedure type="trigger_action" >
 *   <action name="" key="" context="" />
 * </procedure>
 * 
 */
class LibGenfTreeNodeProcedureTriggerAction
  extends LibGenfTreeNodeProcedure
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  
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
   * @return string
   */
  public function getAction()
  {
    
    return trim( $this->node->action['name'] );

  }//end public function getAction */
  
  /**
   * @return string
   */
  public function getActionClass()
  {
    
    return SParserString::subToCamelCase(trim( $this->node->action['name'] ));

  }//end public function getAction */
  
  /**
   * @return string
   */
  public function getKey()
  {
    
    return SParserString::subToCamelCase(trim( $this->node->action['key'] ));

  }//end public function getKey */
  
  /**
   * Der Context der Action
   * 
   * - dataset
   * - entity
   * - whatever
   * 
   * Der einzig relevante context ist im moment dataset oder != dataset,
   * da bei dataset die entity des datensatzes als parameter übergeben
   * werden muss
   * 
   * @return string
   */
  public function getContext()
  {
    
    return trim( $this->node->action['context'] );

  }//end public function getContext */

}//end class LibGenfTreeNodeProcedureTriggerAction

