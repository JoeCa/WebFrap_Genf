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
class LibGenfTreeNodeManagementHead
  extends LibGenfTreeNode
{

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;


////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * any approximations about the datavolume in this table?
   *
   */
  public function expectedDataVolumen()
  {

    if( isset($this->node->data['volum']) )
    {
      return trim($this->node->data['volum']);
    }
    elseif( $this->management->entity->head )
    {
      return $this->management->entity->head->expectedDataVolumen();
    }
    else
    {
      return 'default';
    }

  }//end public function expectedDataVolumen */


}//end class LibGenfTreeNodeEntityHead

