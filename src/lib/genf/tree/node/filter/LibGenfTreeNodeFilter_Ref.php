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
 * 
  <check type="custom" name="custom_filter" >
  </check>
 * 
 */
class LibGenfTreeNodeFilter_Ref
  extends LibGenfTreeNodeFilterCheck
{
  
  /**
   * Type des filters
   * @var string
   */
  public $type = 'ref';
  
  /**
   * @return string
   */
  public function getKey()
  {
    return trim( $this->node['key'] );
  }//end public function getKey */


}//end class LibGenfTreeNodeFilter_Ref

