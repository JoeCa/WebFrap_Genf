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
 * <procedure type="send_mail" >
 * 
 *   <message name="need_more_information" />
 *   
 *   <messages>
 *     <success>
 *       <text lang="en" >Notified Assignment Creator</text>
 *     </success>
 *   </messages>
 *   
 *   <receivers>
 *     <receiver type="responsible" name="responsible" />
 *   </receivers>
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeConditionHasSomewhere
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
  public $generatorKey = 'HasRoleSomewhere';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string
   */
  public function getRoleName()
  {
    
    return trim( $this->node->role['name'] );

  }//end public function getRoleName */
  
  /**
   * @return string
   */
  public function getRelation()
  {
    
    if( !isset($this->node->relation) )
      return 'dataset';
    
    return trim( $this->node->relation );

  }//end public function getRoleName */

  
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

}//end class LibGenfTreeNodeConditionHasSomewhere

