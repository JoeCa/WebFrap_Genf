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
class LibGenfTreeNodeConceptAcl
  extends LibGenfTreeNodeConcept
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function aclEntity()
  {
    if(!isset($this->node->entity))
      return false;

    return true;

  }//end public function aclEntity */

  /**
   *
   */
  public function aclDataset()
  {
    if(!isset($this->node->dataset))
      return false;

    return true;
  }//end public function aclDataset */


}//end class LibGenfTreeNodeConceptAcl

