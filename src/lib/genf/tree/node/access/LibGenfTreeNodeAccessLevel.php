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
class LibGenfTreeNodeAccessLevel
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var int
   */
  public $denied      = 0;

  /**
   * @var int
   */
  public $listing     = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $access      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $assign      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $insert      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $update      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $delete      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $publish     = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $maintenance = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $admin       = User::LEVEL_FULL_ACCESS;


  /**
   * @var int
   */
  public $refDenied      = 0;

  /**
   * @var int
   */
  public $refListing     = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refAccess      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refAssign      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refInsert      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refUpdate      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refDelete      = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refPublish     = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refMaintenance = User::LEVEL_FULL_ACCESS;

  /**
   * @var int
   */
  public $refAdmin       = User::LEVEL_FULL_ACCESS;

  /**
   *
   * @param SimpleXmlElement $node
   */
  public function __construct( $node = null )
  {

    if( !is_null($node) )
    {
      if( isset( $node->listing ) )
        $this->listing = trim($node->listing);

      if( isset( $node->access ) )
        $this->access = trim($node->access);

      if( isset( $node->assign ) )
        $this->assign = trim($node->assign);

      if( isset( $node->insert ) )
        $this->insert = trim($node->insert);

      if( isset( $node->update ) )
        $this->update = trim($node->update);

      if( isset( $node->delete ) )
        $this->delete = trim($node->delete);

      if( isset( $node->publish ) )
        $this->publish = trim($node->publish);

      if( isset( $node->maintenance ) )
        $this->maintenance = trim($node->maintenance);

      if( isset( $node->admin ) )
        $this->admin = trim($node->admin);


      if( isset( $node->ref_listing ) )
        $this->refListing = trim($node->ref_listing);

      if( isset( $node->ref_access ) )
        $this->refAccess = trim($node->ref_access);

      if( isset( $node->ref_assign ) )
        $this->refAssign = trim($node->ref_assign);

      if( isset( $node->ref_insert ) )
        $this->refInsert = trim($node->ref_insert);

      if( isset( $node->ref_update ) )
        $this->refUpdate = trim($node->ref_update);

      if( isset( $node->ref_delete ) )
        $this->refDelete = trim($node->ref_delete);

      if( isset( $node->ref_publish ) )
        $this->refPublish = trim($node->ref_publish);

      if( isset( $node->ref_maintenance ) )
        $this->refMaintenance = trim($node->ref_maintenance);

      if( isset( $node->ref_admin ) )
        $this->refAdmin = trim($node->ref_admin);
    }


  }//end public function __construct */


}//end class LibGenfTreeNodeAccess

