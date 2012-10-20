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
class LibGenfTreeNodeAccessCheck_Level
  extends LibGenfTreeNodeAccessCheck
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var string
   */
  public $type = 'level';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string
   */
  public function getMaxLevel()
  {

    if( isset( $this->node['max_level'] ) )
      return strtoupper( trim( $this->node['max_level'] ) );
    else
      return null;

  }//end public function getMaxLevel */
  
  /**
   * @return string
   */
  public function getUserLevel()
  {
    
    if( !isset($this->node->level['user']) )
      return null;
      
    return trim($this->node->level['user']);
    
  }//end public function getUserLevel */
  
  /**
   * @return string
   */
  public function targetMaxLevel()
  {
    
    if( !isset($this->node->level['max']) )
      return null;
      
    return trim($this->node->level['max']);
    
  }//end public function targetMaxLevel */
  
  /**
   * @return string
   */
  public function targetMinLevel()
  {
    
    if( !isset($this->node->level['min']) )
      return null;
      
    return trim($this->node->level['min']);
    
  }//end public function targetMinLevel */


}//end class LibGenfTreeNodeAccessCheck_Level

