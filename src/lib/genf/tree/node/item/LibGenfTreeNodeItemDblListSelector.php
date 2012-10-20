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
class LibGenfTreeNodeItemDblListSelector
  extends LibGenfTreeNodeItem
{

  /**
   * @return string
   */
  public function getCatridgeClass()
  {
    return 'ItemDblListSelector';
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
  public function getRefKey()
  {
    
    return $this->node['ref'];
    
  }//end public function getRefKey */
  
  /**
   * @return string
   */
  public function getFieldName()
  {
    
    return $this->node->field['name'];
    
  }//end public function getFieldName */

  /**
   * @return string
   */
  public function getOrderBy()
  {
    
    return $this->node->order_by['name'];
    
  }//end public function getOrderBy */

}//end class LibGenfTreeNodeItemDblListSelector

