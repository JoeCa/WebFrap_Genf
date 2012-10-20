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
class LibGenfTreeNodeUiButtonAction_Request
  extends LibGenfTreeNodeUiButtonAction
{

  /**
   * @return string
   */
  public function getUrl()
  {
    
    return trim($this->node->request['url']);
    
  }//end public function getUrl */
  
  /**
   * @return string
   */
  public function getMethod()
  {
    
    return strtolower(trim($this->node->request['method']));
    
  }//end public function getMethod */
  
  /**
   * @return array<string:string>
   */
  public function getParams()
  {
    
    if( !isset( $this->node->params  ) )
      return null; 
      
    $params = array();
    
    foreach( $this->node->params as $param )
    {
      $params[trim($param['name'])] = trim($param['value']);
    }
    
    return $params;
    
  }//end public function getParams */
  
  /**
   * @return array<string:string>
   */
  public function getBodyParams()
  {
    
    if( !isset( $this->node->body  ) )
      return null; 
      
    $params = array();
    
    foreach( $this->node->body as $param )
    {
      $params[trim($param['name'])] = trim($param['value']);
    }
    
    return $params;
    
  }//end public function getBodyParams */

}//end class LibGenfTreeNodeUiButtonAction_Request

