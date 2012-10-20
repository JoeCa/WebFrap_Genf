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
class LibGenfTreeNodeSkeletonService
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameSkeletonService( $this->node );

  }//end protected function loadChilds */
  
  /**
   * @return [string]
   */
  public function getViews()
  {
    
    $views = array();
    
    foreach( $this->views->view as $view )
    {
      $views[] = strtolower(trim( $view['type'] ));
    }

    return $views;
    
  }//end public function getViews */
  
  /**
   * Die HTTP Methoden welche beim Zugriff auf den Service erlaub sind
   * @return [string]
   */
  public function getMethodes()
  {
    
    $methodes = array();
    
    foreach( $this->methodes->method as $method )
    {
      $methodes[] = strtoupper(trim( $method['type'] ));
    }

    return $methodes;
    
  }//end public function getMethodes */
  
  /**
   * @return [LibGenfTreeNodeParam]
   */
  public function getRequestParams()
  {

    $params = array();
    
    foreach( $this->node->request->params->param as $param )
    {
      $params[] = new LibGenfTreeNodeParam( $param );
    }

    return $params;
    
  }//end public function getRequestParams */
  
  /**
   * @return [LibGenfTreeNodeParam]
   */
  public function getDataParams()
  {

    $params = array();
    
    foreach( $this->node->request->data->param as $param )
    {
      $params[] = new LibGenfTreeNodeParam( $param );
    }

    return $params;
    
  }//end public function getDataParams */

  
}//end class LibGenfTreeNodeSkeletonService */

