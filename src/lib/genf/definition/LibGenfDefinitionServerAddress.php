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
class LibGenfDefinitionServerAddress
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

    if( null != $vars->name )
      $name = $vars->name.'_';
    else
      $name = '';

    $list = array();

    $xml = <<<XMLS
      <attribute name="{$name}server" type="text" size="250"  >

        <label>
          <text lang="de" >{$vars->label->de} Server-Addresse</text>
          <text lang="en" >{$vars->label->en} Server Address</text>
        </label>

        <description>
          <text lang="de" >{$vars->label->de} Server-Addresse für {$vars->entity->label->de}</text>
          <text lang="en" >{$vars->label->en} Server Address for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$name}port" type="int"  >

        <label>
          <text lang="de" >{$vars->label->de} Port</text>
          <text lang="en" >{$vars->label->en} port</text>
        </label>

        <description>
          <text lang="de" >{$vars->label->de} Server Port für {$vars->entity->label->de}</text>
          <text lang="en" >{$vars->label->en} server port for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionServerAddress
