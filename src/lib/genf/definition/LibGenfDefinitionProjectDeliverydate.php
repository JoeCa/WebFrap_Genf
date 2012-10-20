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
class LibGenfDefinitionProjectDeliverydate
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
      <attribute name="{$pre}delivery_date" type="date"   >
        <categories main="schedule" />
        {$hidden}
      </attribute>
XMLS;
    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}delivery_date_whish" type="date"   >
        <categories main="schedule" />
        {$hidden}
      </attribute>
XMLS;
    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}delivery_date_offer" type="date"  >
        <categories main="schedule" />
        {$hidden}
      </attribute>
XMLS;
    $nodes[] = $this->addNode($xml, $parentNode);

    $xml = <<<XMLS
      <attribute name="{$pre}confirmed_delivery_date" type="date"  >
        <categories main="schedule" />
        {$hidden}
      </attribute>
XMLS;
    $nodes[] = $this->addNode($xml, $parentNode);

    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionProjectSchedule
