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
class LibGenfTreeNodeActionTarget
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $root = $this->builder->getRoot('management');

    if(!isset($this->node['name']))
      throw new LibGenfModel_Exception('Invalid node format for target');


    if( isset( $this->node['management'] ) && !$management = $root->getManagement( trim($this->node['management']) ) )
    {
      throw new LibGenfModel_Exception('Requested nonexisting target management '.trim($this->node['management']) );
    }
    elseif( !$management = $root->getManagement( trim($this->node['name']) ) )
    {
      throw new LibGenfModel_Exception('Requested nonexisting target management '.trim($this->node['name']) );
    }

    $this->management = $management;

  }//end protected function loadChilds */


  /**
   * @return string
   */
  public function getAction()
  {

    return null;

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

    $href = 'maintab.php?c='.$this->management->name->classUrl.'.'.$this->node['action'];

    if( isset($this->node['ltype']) )
    {
      $href .= '&amp;ltype='.trim($this->node['ltype']);
    }
    
    return $href;
    
  }//end public function getHref */

  /**
   * @return string
   */
  public function getTarget()
  {

    if(isset($this->node['target']))
      return trim($this->node['target']);
    else
      return null;

  }//end public function getTarget */

  /**
   * @return string
   */
  public function getRequestType()
  {

    if(!isset($this->node['view_type']))
      return 'wcm wcm_req_mtab';
    else
      return 'wcm wcm_req_'.trim($this->node['view_type']);

  }//end public function getRequestType */

  /**
   * @return string
   */
  public function getType()
  {

    if(!isset($this->node['view_type']))
      return null;
    else
      return trim($this->node['view_type']);

  }//end public function getType */

}//end class LibGenfTreeNodeActionTarget

