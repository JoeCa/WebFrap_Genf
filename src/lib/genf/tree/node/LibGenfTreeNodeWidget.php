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
class LibGenfTreeNodeWidget
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @var LibGenfTreeNodeUi
   */
  public $ui            = null;

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management   = null;

/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameWidget( $this->node );

    $this->management = $this->builder->getManagement( $this->name->mask );
    
    if( !$this->management )
    {
      $this->isInvalid = true;
      
      $this->error( "Invalid Widget Node: ".$this->debugData() );
      return;
    }

    // only exists if subnode exists
    if( isset( $this->node->ui ) )
    {
      
      $uiClassName          = $this->builder->getNodeClass( 'Ui' );
      $this->ui             = new $uiClassName( $this->node->ui );
      $this->ui->management = $this->management;
      
      $this->ui->setFallback( $this->management->getUi() );
      
    }
    else if( $this->management )
    {
      
      $this->ui = $this->management->getUi();
      
    }

  }//end protected function loadChilds */

/*//////////////////////////////////////////////////////////////////////////////
// ui getter
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {
    return $this->ui;
  }//end public function getUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context )
  {
    
    if( !$this->ui )
      return null;

    return $this->ui->getListUi( $context );

  }//end public function getListUi */

}//end class LibGenfTreeNodeWidget

