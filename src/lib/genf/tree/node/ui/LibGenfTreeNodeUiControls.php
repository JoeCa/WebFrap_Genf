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
class LibGenfTreeNodeUiControls
  extends LibGenfTreeNode
{

  /**
   * @return array
   */
  public function getActionKeys()
  {

    return $this->node->xpath('./action@name');

  }//end public function getActionKeys */

  /**
   * @param string $key
   * @return boolean
   */
  public function hasAction( $key )
  {

    return (boolean)count($this->node->xpath('./action[@name="'.$key.'"]'));

  }//end public function hasAction */

}//end class LibGenfTreeNodeUiControls

