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
class LibGenfTreeNodeItemCustomCloud
  extends LibGenfTreeNodeItem
{

  /**
   * @return string
   */
  public function getCatridgeClass()
  {
    return 'ItemCustomCloud';
  }//end public function getCatridgeClass */
  

  /**
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameItem( $this->node );
    
    if( isset($this->node->access) )
    {
      $this->access = new LibGenfTreeNodeElementAccess($this->node->access);
    }

  }//end protected function loadChilds */
  
  /**
   * @return LibGenfTreeNodeElementAccess
   */
  public function getAccess()
  {

    return $this->access;

  }//end public function getAccess */
  
  /**
   * @return string
   */
  public function getServiceKey()
  {
    if( isset( $this->node->service['key'] )  )
    {
      return trim($this->node->service['key']);
    }
    else
    {
      return null;
    }
    
  }//end public function getServiceKey */
  
  /**
   * @return string
   */
  public function getServiceBaseUrl()
  {
    if( isset( $this->node->service['key'] )  )
    {
      return 'ajax.php?c='.SParserString::subToUrl(trim($this->node->service['key'])) ;
    }
    else
    {
      return null;
    }
    
  }//end public function getServiceBaseUrl */


}//end class LibGenfTreeNodeItemCustomCloud

