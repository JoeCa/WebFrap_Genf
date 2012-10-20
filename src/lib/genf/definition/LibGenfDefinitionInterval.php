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
class LibGenfDefinitionInterval
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


    $vars->name = 'number';
    $vars->type = 'int';
  
    $vars->uiElement->type  = 'int';
    $vars->uiElement->position->priority  = '65';

    if( $vars->name != 'number' )
    {
      $label =<<<CODE
      <label>
        <text lang="de" >{$vars->name} Start Wert</text>
        <text lang="en" >{$vars->name} start value</text>
      </label>
CODE;
    }
    else
    {
      $label =<<<CODE
      <label>
        <text lang="de" >Start Wert</text>
        <text lang="en" >start value</text>
      </label>
CODE;
    }

    $list = array();

    $xml = <<<XMLS
      <attribute name="{$vars->name}_start" type="{$vars->type}"  >

        {$label}
        {$vars->search}
        {$vars->uiElement}
        
        <description>
          <text lang="de" >Start Wert für {$vars->entity->label->de}</text>
          <text lang="en" >start value for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;

    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    if( $vars->name != 'number' )
    {
      $label =<<<CODE
      <label>
        <text lang="de" >{$vars->name} End Wert</text>
        <text lang="en" >{$vars->name} end value</text>
      </label>
CODE;
    }
    else
    {
      $label =<<<CODE
      <label>
        <text lang="de" >End Wert</text>
        <text lang="en" >end value</text>
      </label>
CODE;
    }

    $xml = <<<XMLS
      <attribute name="{$vars->name}_end" type="{$vars->type}"  >

        {$label}

        {$vars->search}
        {$vars->uiElement}

        <description>
          <text lang="de" >End Wert für {$vars->entity->label->de}</text>
          <text lang="en" >end value for {$vars->entity->label->en}</text>
        </description>

      </attribute>
XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionInterval
