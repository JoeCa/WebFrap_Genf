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
    <check name="project_staff" type="parent_assigned" >
      <parent_field attribute="id_project" />
      <assignment reference="human_resources" />
    </check>
 * 
 */
class LibGenfTreeNodeFilter_ParentAssigned
  extends LibGenfTreeNodeFilterCheck
{
  
  /**
   * Type des filters
   * @var string
   */
  public $type = 'parent_assigned';
  
  /**
   * @return string
   */
  public function getParentField()
  {
    return trim( $this->node->parent_field['attribute'] );
  }//end public function getParentField */

  /**
   * @return string
   */
  public function getReferenceName()
  {
    return trim( $this->node->assignment['reference'] );
  }//end public function getReferenceName */

}//end class LibGenfTreeNodeFilterParentAssigned

