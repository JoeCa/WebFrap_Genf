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
class LibGenfTreeNodeConditionHasRolesExplicit
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
  public $generatorKey = 'HasRolesExplicit';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return array()
   */
  public function getRoles()
  {
    
    $roles = array();
    
    foreach( $this->node->roles->role as $role )
    {
      $roles[] = trim( $role['name'] );
    }
    
    return $roles;

  }//end public function getRoles */
  
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
    
    return trim( $this->node->area['name'] );

  }//end public function getAreaKey */
  
  /**
   * @return string
   */
  public function getAreaKeyClass()
  {
    
    if( !isset( $this->node->area['name'] ) )
      return null;
    
    return SParserString::subToCamelCase( trim( $this->node->area['name'] ) ) ;

  }//end public function getAreaKeyClass */

}//end class LibGenfTreeNodeConditionHasSomewhere

