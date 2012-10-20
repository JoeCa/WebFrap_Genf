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
class LibGenfTreeNodeAttributeSemantic
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @return LibGenfTreeNodeSemanticUnit
   */
  public function getUnit()
  {

    if( isset($this->node->unit) )
      return new LibGenfTreeNodeSemanticUnit($this->node->unit);
    else
      return null;

  }



}//end class LibGenfTreeNodeAttributeSemantic

