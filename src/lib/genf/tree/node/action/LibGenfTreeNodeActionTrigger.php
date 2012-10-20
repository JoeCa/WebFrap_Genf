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
class LibGenfTreeNodeActionTrigger
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return string
   */
  public function getAction()
  {

    if(isset($this->node['action']))
      return trim($this->node['action']);
    else
      return trim($this->node);

  }//end public function getAction */

  /**
   * @return string
   */
  public function getContext()
  {

    if(isset($this->node['context']))
      return trim($this->node['context']);
    else
      return null;

  }//end public function getContext */

  /**
   * @return string
   */
  public function getHref()
  {

    if(isset($this->node['href']))
      return trim($this->node['href']);
    else
      return trim($this->node);

  }//end public function getHref */

  /**
   * @return string
   */
  public function getTarget()
  {

    if(isset($this->node['target']))
      return trim($this->node['target']);
    elseif( $this->node['mgmt'] )
      return trim($this->node['mgmt']);
    else
      return null;

  }//end public function getTarget */
  
  /**
   * @return string
   */
  public function getTargetCall()
  {

    if( $this->node['call'] )
      return trim($this->node['call']);
    else
      return null;

  }//end public function getTargetCall */

  
  /**
   * @return string
   */
  public function getRequestType()
  {

    if(!isset($this->node['type']))
      return 'wcm wcm_req_mtab';
    else
      return 'wcm wcm_req_'.trim($this->node['type']);

  }//end public function getRequestType */

  /**
   * @return string
   */
  public function getType()
  {

    if( !isset( $this->node['type'] ) )
      return null;
    else
      return strtolower(trim( $this->node['type'] ));

  }//end public function getType */

}//end class LibGenfTreeNodeActionTrigger

