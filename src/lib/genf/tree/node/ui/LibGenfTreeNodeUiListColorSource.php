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
 *
 *  <color_source attribute="id_status" >
 *    <background field="bg_color"  />
 *    <text field="text_color" />
 *  </color_source>
 *  
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiListColorSource
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {
  }//end protected function loadChilds */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string 
   */
  public function getAttrField()
  {
    
    return trim( $this->node['attribute'] );
    
  }//end public function getAttrField */
  
  /**
   * @return string 
   */
  public function getAttrSource()
  {
    
    return isset( $this->node['src'] ) 
      ? trim( $this->node['src'] )
      : null;
    
  }//end public function getAttrField */
  
  /**
   * @return string 
   */
  public function getBackgroundField()
  {
    
    return isset( $this->node->background['field'] ) 
      ? trim( $this->node->background['field'] )
      : null;
    
  }//end public function getBackgroundField */
  
  /**
   * @return string 
   */
  public function getTextField()
  {
    
    return isset( $this->node->text['field'] ) 
      ? trim( $this->node->text['field'] )
      : null;
    
  }//end public function getTextField */
  
  /**
   * @return string 
   */
  public function getBorderField()
  {
    
    return isset( $this->node->border['field'] ) 
      ? trim( $this->node->border['field'] )
      : null;
    
  }//end public function getBorderField */

}//end class LibGenfTreeNodeUiListing

