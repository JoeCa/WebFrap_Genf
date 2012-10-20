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
class LibGenfDefinitionProjectFunding
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

    $node = $this->tree->simple( $statement );

    // remove statement
    $this->remove( $statement );

    $pre    = '';
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
      <attribute name="{$pre}planned_funding" is_a="budget" >
        <categories main="funding" />
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}funding" is_a="budget" >
        <categories main="funding" />
        {$hidden}
      </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);


    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionProjectFunding
