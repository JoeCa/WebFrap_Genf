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
class LibGenfDefinitionUuid
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

    $vars = $this->checkAttribute( $statement, $parentNode );

    if( !$vars->name )
      $vars->name = 'm_uuid';

    $xml = <<<XMLS
      <attribute name="{$vars->name}" type="uuid" size="32" validator="uuid"  >

        <!-- name is not always unique, but can be used to identify something -->
        <unique strength="full" />

        <search type="equal" />
        <categories main="meta" ></categories>

        <label>
          <text lang="de" >UUID</text>
          <text lang="en" >UUID</text>
        </label>

        <description>
          <text lang="de" >UUID für {$vars->entity->label->de}</text>
          <text lang="en" >UUID for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;

    return $this->replaceDefinition($xml, $statement, $parentNode);

  }//end public function interpret */


} // end class LibGenfDefinitionUuid
