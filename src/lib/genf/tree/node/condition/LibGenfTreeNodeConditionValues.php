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
 * <condition type="values" >
 * 
 *   <fields>
 *     <field type="responsible" name="responsible" />
 *   </fields>
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeConditionValues
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
  public $generatorKey = 'Values';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  
  /**
   * Extrahieren der tabellen fields aus dem ui layout
   * @param LibGenfEnv $env
   * @return [LibGenfTreeNodeConditionField]
   */
  public function getFields( $env )
  {

    $fieldClassName  = $this->builder->getNodeClass( 'Condition_Field' );

    if( !isset( $this->node->fields->field ) )
      return array();
    
    $management = $env->getMgmt();

    $fields = array();

    foreach( $this->node->fields->field as $field )
    {
      
      $fieldNode = new $fieldClassName( $field );
      
      if( isset( $field['ref'] ) )
        $fieldNode->ref = $management->entity->getReference( trim($field['ref']) );
      
      $fields[] = $fieldNode;
    }

    return $fields;

  }//end public function getFields */

  /**
   * Checken ob die Fields getyped sind, wenn ja rückgabe des types
   * @return string
   */
  public function getFieldsType(  )
  {

    if( isset( $this->node->fields['type'] ) )
      return trim( $this->node->fields['type'] );
    
    return null;
   
  }//end public function getFieldsType */

}//end class LibGenfTreeNodeConditionValues

