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
class LibGenfEnvSubpage
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * Type der Environment
   * @var string
   */
  public $type      = 'env_subpage';


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

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->management->getAccess() )
    {
      return $access;
    }
    
    return null;
    
  }//end public function getAccess */

}//end class LibGenfEnvSubpage */
