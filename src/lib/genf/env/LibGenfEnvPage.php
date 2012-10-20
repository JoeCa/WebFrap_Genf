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
class LibGenfEnvPage
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  public $type      = 'env_page';


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeSubpage $page
   */
  public function __construct( $builder, $page  )
  {

    $this->builder = $builder;
    $this->setData( $page );

  }//end public function __construct */


  /**
   * @param LibGenfTreeNodeSubpage $page
   */
  public function setData( $page )
  {

    $this->management   = $page;
    $this->name         = $page->name;

  }//end public function setData */


}//end class LibGenfEnvPage */
