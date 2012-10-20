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
class LibGenfTreeNodeConditionReferences
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
  public $generatorKey = 'References';
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  
  /**
   * Extrahieren der tabellen fields aus dem ui layout
   * @param LibGenfEnv $env
   * @return array[LibGenfTreeNodeConditionReference]
   */
  public function getReferences( $env )
  {

    $refClassName  = $this->builder->getNodeClass( 'ConditionReference' );

    if( !isset( $this->node->references->ref ) )
      return array();
  

    $references = array();

    foreach( $this->node->references->ref as $ref )
    {
      
      $refNode = new $refClassName( $ref );

      $references[] = $refNode;
    }

    return $references;

  }//end public function getReferences */


}//end class LibGenfTreeNodeConditionReferences

