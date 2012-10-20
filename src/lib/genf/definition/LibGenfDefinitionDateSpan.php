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
class LibGenfDefinitionDateSpan
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


    $vars->name = 'date';
    $vars->type = 'date';
  
    $vars->uiElement->type  = 'date';
    $vars->uiElement->position->priority  = '45';

    if( $vars->name != 'date' )
    {
      $label =<<<CODE
      <label>
        <text lang="de" >{$vars->name} Start Datum</text>
        <text lang="en" >{$vars->name} Start Date</text>
      </label>
CODE;
    }
    else
    {
      $label =<<<CODE
      <label>
        <text lang="de" >Start Datum</text>
        <text lang="en" >Start Date</text>
      </label>
CODE;
    }

    $list = array();

    $xml = <<<XMLS
      <attribute name="{$vars->name}_start" type="date"  >

        {$label}
        {$vars->search}
        {$vars->uiElement}
        
        <description>
          <text lang="de" >Start Datum für {$vars->entity->label->de}</text>
          <text lang="en" >Start Date for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;

    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    if( $vars->name != 'date' )
    {
      $label =<<<CODE
      <label>
        <text lang="de" >{$vars->name} End Datum</text>
        <text lang="en" >{$vars->name} End Date</text>
      </label>
CODE;
    }
    else
    {
      $label =<<<CODE
      <label>
        <text lang="de" >End Datum</text>
        <text lang="en" >End Date</text>
      </label>
CODE;
    }

    $xml = <<<XMLS
      <attribute name="{$vars->name}_end" type="date"  >

        {$label}

        {$vars->search}
        {$vars->uiElement}

        <description>
          <text lang="de" >End Datum für {$vars->entity->label->de}</text>
          <text lang="en" >End Date for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionDateSpan
