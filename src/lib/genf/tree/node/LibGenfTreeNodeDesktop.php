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
class LibGenfTreeNodeDesktop
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @var LibGenfTreeNodeNavigation
   */
  protected $navigation = null;

  /**
   *
   * @var LibGenfTreeNodePanel
   */
  protected $panel = null;

  /**
   *
   * @var LibGenfTreeNodeTree
   */
  protected $mainmenu = null;

  /**
   *
   * @var LibGenfTreeNodeWorkarea
   */
  protected $workarea = null;

  /**
   *
   * @var LibGenfTreeNodeTree
   */
  protected $tree = null;

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDesktop( $this->node );

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function inherits()
  {

    if( isset( $this->node['extends'] ) )
      return new LibGenfNameDefault( trim($this->node['extends']) );
    else
      return null;

  }//end public function inherits */
  
  /**
   * Valide typen:
   * 
   * - megamenu
   * - treenav
   * 
   * @return string
   */
  public function getType()
  {

    if( isset( $this->node['type'] ) )
      return trim($this->node['type']);
    else
      return 'treenav';

  }//end public function getType */


  /**
   * @return array[LibGenfTreeNodeDesktopContainer]
   */
  public function getContainers()
  {

    $cotainers = array();

    if(!isset($this->node->workarea->containers->container))
      return $cotainers;

    foreach ( $this->node->workarea->containers->container as $container )
    {
      $cotainers[] = new LibGenfTreeNodeDesktopContainer( $container );
    }

    return $cotainers;


  }//end public function getContainers */

/*//////////////////////////////////////////////////////////////////////////////
// navigation code
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return boolean
   */
  public function hasNavigation()
  {
    return isset($this->node->navigation);
  }//end public function hasNavigation */

  /**
   * @return LibGenfTreeNodeNavigation
   */
  public function getNavigation()
  {

    if(!isset($this->node->navigation))
      return null;

    if( $this->navigation )
      return $this->navigation;

    $classNavigation  = $this->builder->getNodeClass('DesktopNavigation');
    $this->navigation = new $classNavigation( $this->node->navigation ) ;

    return $this->navigation;

  }//end public function getNavigation */


/*//////////////////////////////////////////////////////////////////////////////
// navigation code
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return boolean
   */
  public function hasPanel()
  {
    return isset($this->node->panel);
  }//end public function hasPanel */

  /**
   * @return LibGenfTreeNodeDesktopPanel
   */
  public function getPanel()
  {

    if(!isset($this->node->panel))
      return null;

    if( $this->panel )
      return $this->panel;

    $classPanel  = $this->builder->getNodeClass('DesktopPanel');
    $this->panel = new $classPanel( $this->node->panel ) ;

    return $this->panel;

  }//end public function getPanel */


/*//////////////////////////////////////////////////////////////////////////////
// navigation code
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return boolean
   */
  public function hasMainmenu()
  {
    return isset($this->node->mainmenu);
  }//end public function hasMainmenu */

  /**
   * @return LibGenfTreeNodeTree
   */
  public function getMainmenu()
  {

    if( !isset( $this->node->mainmenu ) )
      return null;

    if( $this->mainmenu )
      return $this->mainmenu;
      
    if( isset( $this->node['type'] )  )
    {
      $classKey = 'Tree_'.SParserString::subToCamelCase( trim( $this->node['type'] ) );
    }
    else 
    {
      $classKey = 'Tree';
    }

    $classMenu      = $this->builder->getNodeClass( $classKey );
    $this->mainmenu = new $classMenu( $this->node->mainmenu ) ;

    return $this->mainmenu;

  }//end public function getMainmenu */

  /**
   * @return LibGenfTree
   */
  public function getTree()
  {

    if(!isset($this->node->tree))
      return null;

    if( $this->tree )
      return $this->tree;

    $classMenu  = $this->builder->getNodeClass( 'Tree' );
    $this->tree = new $classMenu( $this->node->tree ) ;

    return $this->tree;

  }//end public function getTree */

/*//////////////////////////////////////////////////////////////////////////////
// workarea
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return boolean
   */
  public function hasWorkarea()
  {
    return isset($this->node->workarea);
  }//end public function hasWorkarea */
  
  /**
   * @return string
   */
  public function workareaType()
  {
    return isset($this->node->workarea['type'])
      ?trim($this->node->workarea['type'])
      :null;
  }//end public function workareaType */
  

  /**
   * @return LibGenfTreeNodeDesktopWorkarea
   */
  public function getWorkarea()
  {

    if( !isset($this->node->workarea) )
      return null;

    if( $this->workarea )
      return $this->workarea;

    $classMenu  = $this->builder->getNodeClass('DesktopWorkarea');
    $this->workarea = new $classMenu( $this->node->workarea ) ;

    return $this->workarea;

  }//end public function getWorkarea */
  
/*//////////////////////////////////////////////////////////////////////////////
// opener handling
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Opener Urls abfragen
   * @return array
   */
  public function getOpener()
  {
    if( !isset( $this->node->opener->tab ) )
      return array();
      
    $callUrls = array();
    
    foreach( $this->node->opener->tab as $tab )
    {
      $callUrls[] = trim( $tab['url'] );
    }
    
    return $callUrls;
      
  }//end public function getOpener */


}//end class LibGenfTreeNodeDesktop

