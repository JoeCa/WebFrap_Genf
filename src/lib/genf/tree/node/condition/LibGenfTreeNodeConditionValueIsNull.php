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
 * @example Tag Beispiel
 * <condition type="value_is_null" >
 *   <attribute name="start_date" />
 * </condition>
 * 
 */
class LibGenfTreeNodeConditionValueIsNull
  extends LibGenfTreeNodeCondition
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Der Key welcher Benötigt wird um einen passenden Generator für diese
   * Condition zu laden
   * @var string
   */
  public $generatorKey = 'ValueIsNull';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string
   */
  public function getAttribute()
  {
    
    return trim( $this->node->attribute['name'] );

  }//end public function getAttribute */
  
  /**
   * @return string
   */
  public function getAreaKey()
  {
    
    if( !isset( $this->node->area['name'] ) )
      return null;
    
    return trim($this->node->area['name']);

  }//end public function getAreaKey */
  
  /**
   * @return string
   */
  public function getAreaKeyClass()
  {
    
    if( !isset( $this->node->area['name'] ) )
      return null;
    
    return SParserString::subToCamelCase( trim($this->node->area['name']) ) ;

  }//end public function getAreaKeyClass */

}//end class LibGenfTreeNodeConditionValueIsNull

