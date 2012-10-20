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
 * <condition type="has_role" >
 * 
 *   <role name="fubar" />
 *   <relation type="dataset" />
 *   <area name="some_area" />
 *   
 * </condition>
 * 
 */
class LibGenfTreeNodeConditionHasRole
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
  public $generatorKey = 'HasRole';
  
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
   * @return LibGenfTreeNodeConstraintRole
   */
  public function getRoleConstraint()
  {
    
    return new LibGenfTreeNodeConstraintRole( $this->node->role );

  }//end public function getRoleConstraint */
  
  /**
   * @return string
   */
  public function getRelation()
  {
    
    if( !isset( $this->node->area['relation'] ) )
      return 'mask';
    
    return trim( $this->node->area['relation'] );

  }//end public function getRelation */
  
  /**
   * @return string
   */
  public function getAccuracy()
  {
    
    // auser dataset macht das für listen vermutlich nur selten sinn
    // ob die einschätzung sich mit der realität deckt sollte aber 
    // beobachtet werde
    if( !isset( $this->node->area['accuracy'] ) )
      return 'dataset';
    
    return trim( $this->node->area['accuracy'] );

  }//end public function getAccuracy */

  
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

}//end class LibGenfTreeNodeConditionHasRole

