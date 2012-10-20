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
class LibGenfDefinitionProjectBudget
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return array<DOMNode>
   */
  public function interpret( $statement, $parentNode )
  {

    //$infos = simplexml_import_dom( $statement);

    $node = $this->tree->simple( $statement );
    $vars = $this->checkAttribute( $statement, $parentNode );

    // remove statement
    $this->remove( $statement );

    $vars->uiElement->type = 'money';
    
    $pre    = null;
    $preTag = '';
    $hidden = '';
    
    if( isset($node->pre) )
    {
      $pre    = trim($node->pre).'_';
      $preTag = '<pre>'.trim($node->pre).'</pre>';
      $hidden = '<hidden />';
    }

    $nodes = array();

    $xml = <<<XMLS
      <attribute name="{$pre}planned_budget" is_a="budget"    >
        <categories main="budget" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}planned_effort" is_a="budget"   >

        <label>
          <text lang="de" >{$pre}Planned Effort [PM]</text>
          <text lang="en" >{$pre}Planned Effort [PM]</text>
        </label>
        <categories main="budget" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}planned_cost" is_a="budget"  >
        <categories main="budget" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}total_budget" is_a="budget"   >
        <categories main="budget" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}total_effort" is_a="budget"   >
        <label>
          <text lang="de" >{$pre}Total Effort [PM]</text>
          <text lang="en" >{$pre}Total Effort [PM]</text>
        </label>
        <categories main="budget" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}total_cost" is_a="budget"  >
        <categories main="budget" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    // only place if no pre isset
    if( !$pre )
    {

      $xml = <<<XMLS
      <attribute name="budget_low_red" is_a="budget"  >
        <categories main="budget_controlling" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

      $xml = <<<XMLS
      <attribute name="budget_low_yellow" is_a="budget"  >
        <categories main="budget_controlling" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

      $xml = <<<XMLS
      <attribute name="budget_high_red" is_a="budget" >
        <categories main="budget_controlling" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="budget_high_yellow" is_a="budget" >
        <categories main="budget_controlling" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

    }


    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionProjectBudget
