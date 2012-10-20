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
class LibGenfDefinitionTimeSpan
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

    $vars = $this->checkAttribute( $statement, $parentNode  );

    $vars->name = 'time';
    $vars->type = 'time';

    $list       = array();

    $xml = <<<XMLS
      <attribute name="{$vars->name}_start" type="time"  >

        <label>
          <text lang="de" >Start {$vars->name}</text>
          <text lang="en" >Start {$vars->name}</text>
        </label>

        <description>
          <text lang="de" >Start Time für {$vars->entity->label->de}</text>
          <text lang="en" >Start Time for {$vars->entity->label->en}</text>
        </description>

      </attribute>

XMLS;


    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$vars->name}_end" type="time"  >

        <label>
          <text lang="de" >End {$vars->name}</text>
          <text lang="en" >End {$vars->name}</text>
        </label>

        <description>
          <text lang="de" >End Time für {$vars->entity->label->de}</text>
          <text lang="en" >End Time for {$vars->entity->label->en}</text>
        </description>

      </attribute>

XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionTitle
