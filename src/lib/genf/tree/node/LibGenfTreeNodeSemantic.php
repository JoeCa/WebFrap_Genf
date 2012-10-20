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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeSemantic
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * any approximations about the datavolume in this table?
   *
   */
  public function expectedDataVolumen()
  {

    return isset($this->node->data_volume)
      ? trim($this->node->data_volume)
      : null;

  }//end public function expectedDataVolumen */


}//end class LibGenfTreeNodeSemantic

