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
class LibGenfEnvProcess
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Type des Environments
   * @var string
   */
  public $type      = 'env_process';
  
  /**
   * Listentype des Management Knotens
   * @var string
   */
  public $ltype      = 'table';

  /**
   * Der Modellknoten des Prozesses
   * @var LibGenfTreeNodeProcess
   */
  public $process    = null;


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeProcess $process
   */
  public function __construct( $builder, $process  )
  {

    $this->builder = $builder;
    $this->setData( $process );

  }//end public function __construct */
  
  /**
   * @return LibGenfNameProcess
   */
  public function getName()
  {
    return $this->process->name;
  }//end public function getName */

  /**
   * @param LibGenfTreeNodeProcess $process
   */
  public function setData( $process )
  {

    $this->process    = $process;
    $this->name       = $process->name;

    $this->management = $process->getManagement();
    
    $this->ltype = 'table';
    
    if( $this->management->concept( 'tree' ) )
      $this->ltype = 'treetable';

  }//end public function setData */
  
  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->process->getAccess() )
    {
      return $access;
    }
    
    return null;
    
  }//end public function getAccess */


}//end class LibGenfEnvProcess */
