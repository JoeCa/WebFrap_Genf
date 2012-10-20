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
class LibGenfTreeNodeFieldOrder
  extends LibGenfTreeNode
{

  /**
   * @return string
   */
  public function name()
  {
    return trim($this->node['name']);
  }//end public function name */

  /**
   * @return string
   */
  public function adjustment()
  {
    
    if( isset($this->node['adjustment']) )
      return trim($this->node['adjustment']);
      
    if( isset($this->node['order']) )
      return trim($this->node['order']);
    
    return  '';

  }//end public function adjustment */

}//end class LibGenfTreeNodeFieldOrder

