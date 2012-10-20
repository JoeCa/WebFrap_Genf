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
class LibGenfDefinitionDateTimeSpan
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return [DOMNode]
   */
  public function interpret( $statement, $parentNode )
  {

    $vars = $this->checkAttribute( $statement, $parentNode  );


    $vars->name = 'timestamp';
    $vars->type = 'timestamp';


    if( $vars->name != 'date' )
    {
      $label =<<<CODE
      <label>
        <text lang="de" >{$vars->name} Start</text>
        <text lang="en" >{$vars->name} Start</text>
      </label>
CODE;
    }
    else
    {
      $label =<<<CODE
      <label>
        <text lang="de" >Start</text>
        <text lang="en" >Start</text>
      </label>
CODE;
    }

    $list = array();

    $xml = <<<XMLS
      <attribute name="{$vars->name}_start" type="timestamp"  >

        {$label}
        {$vars->search}

        <description>
          <text lang="de" >Start Zeitpunkt für {$vars->entity->label->de}</text>
          <text lang="en" >Start Date for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;

    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    if( $vars->name != 'date' )
    {
      $label =<<<CODE
      <label>
        <text lang="de" >{$vars->name} Ende</text>
        <text lang="en" >{$vars->name} End</text>
      </label>
CODE;
    }
    else
    {
      $label =<<<CODE
      <label>
        <text lang="de" >Ende</text>
        <text lang="en" >End</text>
      </label>
CODE;
    }

    $xml = <<<XMLS
      <attribute name="{$vars->name}_end" type="timestamp"  >

        {$label}

        {$vars->search}

        <description>
          <text lang="de" >End Zeitpunkt für {$vars->entity->label->de}</text>
          <text lang="en" >End Date for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionTitle
