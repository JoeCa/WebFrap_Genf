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
class LibGenfTreeNodeProfile
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfTreeNodePermission
   */
  public $permission = null;

/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameProfile( $this->node );

  }//end protected function loadChilds */

  /**
   * @return LibGenfNameDefault
   */
  public function inherits()
  {

    if( isset( $this->node['extends'] ) )
      return new LibGenfNameDefault( trim($this->node['extends']) );
    else
      return null;

  }//end public function inherits */

  /**
   * @return LibGenfNameDefault
   */
  public function getPanel()
  {
    if( isset( $this->node->elements->panel['name'] ) )
      return new LibGenfNameDefault( trim($this->node->elements->panel['name']) );
    else
      return new LibGenfNameDefault( 'default' );
  }//end public function getPanel */

  /**
   * @return LibGenfNameDefault
   */
  public function getDesktop()
  {
    if( isset( $this->node->elements->desktop['name'] ) )
      return new LibGenfNameDefault( trim($this->node->elements->desktop['name']) );
    else
      return new LibGenfNameDefault( 'default' );
  }//end public function getDesktop */

  /**
   * @return LibGenfNameDefault
   */
  public function getMainmenu()
  {
    if( isset( $this->node->elements->mainmenu['name'] ) )
      return new LibGenfNameDefault( trim($this->node->elements->mainmenu['name']) );
    else
      return new LibGenfNameDefault( 'default' );
  }//end public function getMainmenu */

  /**
   * @return LibGenfNameDefault
   */
  public function getNavigation()
  {
    if( isset( $this->node->elements->navigation['name'] ) )
      return new LibGenfNameDefault( trim($this->node->elements->navigation['name']) );
    else
      return new LibGenfNameDefault( 'default' );
  }//end public function getNavigation */

  /**
   * @return array
   */
  public function getGetters()
  {

    return array();

  }//end public function getGetters */
  
  
  /**
   * @return [LibGenfTreeNodeProfileQuicklink]
   */
  public function getQuickLinks()
  {

    if( !isset( $this->node->quick_links->link ) )
      return array();
      
    $links = array();
    
    foreach( $this->node->quick_links->link as $link )
    {
      $links[] = new LibGenfTreeNodeProfileQuicklink($link);
    }
    
    return $links;

  }//end public function getQuickLinks */

  /**
   * 
   */
  public function level()
  {
    return 'GUEST';
  }//end public function level */
  
  /**
   * @return LibGenfTreeNodePermission
   */
  public function getPermission()
  {
    
    if( $this->permission )
      return $this->permission;
      
    if( !isset( $this->node->permission ) )
      return null;
      
    $this->permission = new LibGenfTreeNodePermission( $this->node->permission );
    
    return $this->permission;
    
  }//end public function getPermission */


}//end class LibGenfTreeNodeProfile

