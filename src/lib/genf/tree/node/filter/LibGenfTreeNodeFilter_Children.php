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
class LibGenfTreeNodeFilter_Children
  extends LibGenfTreeNodeFilterCheck
{
  
  /**
   * Type des filters
   * @var string
   */
  public $type = 'children';
  
  /**
   * @return string
   */
  public function getParentAttr()
  {
    return trim( $this->node->parent['attribute'] );
  }//end public function getParentAttr */

  /**
   * @return string
   */
  public function getChildAttr()
  {
    return trim( $this->node->child['attribute'] );
  }//end public function getChildAttr */

}//end class LibGenfTreeNodeFilter_Children

