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
class LibGenfDefinitionProjectEffort
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

    $vars->uiElement->type = 'numeric';
    
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
      <attribute name="{$pre}planned_manhours" is_a="man_hour"    >
        <categories main="effort" />
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}manhours" is_a="man_hour"   >
        <categories main="effort" />
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
      <attribute name="manhours_low_red" is_a="man_hour"  >
        <categories main="effort_constraint" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

      $xml = <<<XMLS
      <attribute name="manhours_low_yellow" is_a="man_hour"  >
        <categories main="effort_constraint" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

      $xml = <<<XMLS
      <attribute name="manhours_high_red" is_a="man_hour" >
        <categories main="effort_constraint" ></categories>
        {$vars->uiElement}
        {$vars->display}
        {$vars->search}
        {$hidden}
      </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="manhours_high_yellow" is_a="man_hour" >
        <categories main="effort_constraint" ></categories>
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
