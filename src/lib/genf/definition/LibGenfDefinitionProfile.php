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
class LibGenfDefinitionProfile
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    $nodes = array();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'id_profile_ref';
      $vars->type       = 'int';
      $vars->validator  = 'eid';

      $vars->uiElement->type = 'hidden';

      $vars->label->de  = 'Profile';
      $vars->label->en  = 'Profile';
      $vars->label->fr  = 'Profile';

      $vars->description->de  = 'Referenz auf einen profil Datensatz';
      $vars->description->en  = 'Reference to a Profile Dataset';

      /*
      if( isset($statement->optional) )
        $vars->required  = 'false';
      else
        $vars->required  = 'true';
      */

      $nodes[] =  $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    $xml = <<<XMLS
      <attribute name="{$vars->name}_entity" target="wbfsys_entity" >

        <label>
          <text lang="de" >Entity</text>
          <text lang="en" >Entity</text>
        </label>

        <description>
          <text lang="de" >Referenz auf die Entität des Datensatzes für die Virtuelle Verknüpfung</text>
          <text lang="en" >Reference to the entity for the virtual connection</text>
        </description>

        <uiElement type="hidden" />

      </attribute>

XMLS;

    $nodes[] = $this->addNode( $xml, $parentNode );


    return $nodes;

  }//end public function interpret */


}//end class LibGenfDefinitionProfile
