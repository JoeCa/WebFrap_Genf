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
class LibGenfEnvAction
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Type des Environments
   * @var string
   */
  public $type      = 'env_action';
  
  /**
   * Listentype des Management Knotens
   * @var string
   */
  public $ltype      = 'table';

  /**
   * Der Modellknoten des Message
   * @var LibGenfTreeNodeAction
   */
  public $action    = null;


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeAction $action
   */
  public function __construct( $builder, $action  )
  {

    $this->builder = $builder;
    $this->setData( $action );

  }//end public function __construct */

  /**
   * @param LibGenfTreeNodeAction $action
   */
  public function setData( $action )
  {

    $this->action     = $action;
    $this->name       = $action->name;

    $this->management = $action->getManagement();
    
    $this->ltype = 'table';
    
    if( $this->management->concept( 'tree' ) )
      $this->ltype = 'treetable';

  }//end public function setData */
  
  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->message->getAccess() )
    {
      return $access;
    }
    
    return null;
    
  }//end public function getAccess */


}//end class LibGenfEnvAction */
