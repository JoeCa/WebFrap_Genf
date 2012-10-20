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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeCondition_Field
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTreeNodeReference
   */
  public $ref = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @return string
   */
  public function fieldName( )
  {
    // if not exists, that's an error
    if(!isset( $this->node['name'] ) )
      return null;

    return trim( $this->node['name'] );

  }//end public function fieldName */


  /**
   * @return string
   */
  public function reference( )
  {

    if( isset( $this->node['ref'] ) )
    {
      return trim($this->node['ref']);
    }

    // optional
    if( !isset( $this->node['src'] ) )
      return null;

    return trim( $this->node['src'] );

  }//public function reference */

  /**
   * @return string
   */
  public function refType( )
  {
    // optional
    if(!isset( $this->node['ref_type'] ) )
      return null;

    return trim($this->node['ref_type']);

  }//public function refType */
  
////////////////////////////////////////////////////////////////////////////////
// Check Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function isRequired( )
  {
    // optional
    if( !isset( $this->node['required'] ) )
      return false;

    return ('true' == strtolower(trim($this->node['required']))) ;

  }//public function isRequired */
  
  /**
   * @return string
   */
  public function minValue( )
  {
    // optional
    if( !isset( $this->node['min'] ) )
      return null;

    return trim($this->node['min']) ;

  }//public function minValue */
  
  /**
   * @return string
   */
  public function maxValue( )
  {
    // optional
    if( !isset( $this->node['max'] ) )
      return null;

    return trim($this->node['max']) ;

  }//public function maxValue */
  
  /**
   * @return string
   */
  public function isEqual( )
  {
    // optional
    if( !isset( $this->node['equals'] ) )
      return null;

    return trim($this->node['equals']) ;

  }//public function isEqual */
  
  /**
   * @return string
   */
  public function isEmpty( )
  {
    // optional
    if( !isset( $this->node['empty'] ) )
      return null;

    return trim($this->node['empty']) ;

  }//public function isEqual */

}//end class LibGenfTreeNodeConditionField

