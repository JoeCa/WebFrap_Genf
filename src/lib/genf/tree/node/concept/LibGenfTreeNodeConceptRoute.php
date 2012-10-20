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
class LibGenfTreeNodeConceptRoute
  extends LibGenfTreeNodeConcept
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getRouterKey()
  {
    
    if( isset( $this->node['router'] ) )
    {
      return trim($this->node['router']);
    }
    else
    {
      return null;
    }
    
  }//end public function getRouterKey */

  /**
   * @return string
   */
  public function getMainRoute()
  {
    
    if( isset( $this->node->main_route['name'] ) )
    {
      return trim($this->node->main_route['name']);
    }
    else
    {
      return null;
    }
    
  }//end public function getMainRoute */
  
  /**
   * @return string
   */
  public function getMainRouteName()
  {
    
    if( isset( $this->node->main_route['name'] ) )
    {
      return trim($this->node->main_route['name']);
    }
    else
    {
      return null;
    }
    
  }//end public function getMainRoute */

  /**
   * prüfen ob diese route eigenen listenelemente pflegt / besitzt
   */
  public function hasListElement( $type = null )
  {
    
    return true;
    
    if( isset($this->node->ui->list->listing) )
      return true;

    if( $type && isset($this->node->ui->list->{$type}) )
    {
      return true;
    }

    return false;
    
  }//end public function hasListElement */
  
}//end class LibGenfTreeNodeConceptRoute

