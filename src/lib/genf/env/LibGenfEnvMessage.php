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
class LibGenfEnvMessage
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Type des Environments
   * @var string
   */
  public $type      = 'env_message';
  
  /**
   * Listentype des Management Knotens
   * @var string
   */
  public $ltype      = 'table';

  /**
   * Der Modellknoten des Message
   * @var LibGenfTreeNodeMessage
   */
  public $message    = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeMessage $message
   */
  public function __construct( $builder, $message  )
  {

    $this->builder = $builder;
    $this->setData( $message );

  }//end public function __construct */

  /**
   * @param LibGenfTreeNodeMessage $message
   */
  public function setData( $message )
  {

    $this->message    = $message;
    $this->name       = $message->name;

    $this->management = $message->getManagement();
    
    $this->ltype = 'table';
    
    // messages müssen nicht zwangsweise ein management als datenbasis haben
    if( $this->management )
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


}//end class LibGenfEnvMessage */
