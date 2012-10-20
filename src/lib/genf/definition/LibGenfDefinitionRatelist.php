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
class LibGenfDefinitionRatelist
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

    $rateList = simplexml_import_dom( $statement);

    // remove statement
    $this->remove( $statement );

    $nodes = array();

    foreach( $rateList->rate as $rate )
    {

      $name = trim($rate['name']);

      $xml = <<<XMLS
    <attribute name="rate_{$name}"   >
      <uiElement type="input" >Ratingbar</uiElement>
      <categories main="rating" />
    </attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

      $xml = <<<XMLS
    <attribute name="text_{$name}"  >
      <uiElement type="textarea"   >
        <size width="medium" height="small" />
      </uiElement>
      <categories main="rating" />
    </attribute>

XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

    }//end foreach


    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionRatelist
