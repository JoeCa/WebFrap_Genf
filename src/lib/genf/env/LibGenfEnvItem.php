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
class LibGenfEnvItem
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Type des Environments
   * @var string
   */
  public $type      = 'env_item';

  /**
   * Der Modellknoten des Prozesses
   * @var LibGenfTreeNodeItem
   */
  public $item    = null;
  
  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeItem $item
   */
  public function __construct( $builder, $item  )
  {

    $this->builder = $builder;
    $this->setData( $item );

  }//end public function __construct */

  /**
   * @param LibGenfTreeNodeItem $item
   */
  public function setData( $item )
  {

    $this->item        = $item;
    $this->name        = $item->name;

    $this->management  = $this->builder->getManagement( $item->name->source );

  }//end public function setData */
  
  /**
   *
   * @return LibGenfNameItem
   */
  public function getName()
  {
    
    return $this->item->getName();
    
  }//end public function getName */
  
  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->item->getAccess() )
    {
      return $access;
    }
    
    return null;
    
  }//end public function getAccess */


}//end class LibGenfEnvItem */
