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
class LibGenfDefinitionBase
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

    $infos = simplexml_import_dom( $statement);


    // remove statement
    $this->remove( $statement );

    $nodes = array();

    // check if it is specified wich base attributes the node should have
    // if there are no base attributes use name and description as default
    if(  !$infos->count() )
    {

      $xml = <<<XMLS
    <attribute is_a="name" ></attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);


      $xml = <<<XMLS
    <attribute is_a="description"   ></attribute>
XMLS;

      $nodes[] = $this->addNode($xml, $parentNode);

    }
    else // check what nodes are given
    {
      if( isset($infos->name) )
      {
        $xml = <<<XMLS
    <attribute is_a="name"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->label) )
      {
        $xml = <<<XMLS
    <attribute is_a="label"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->title) )
      {
        $xml = <<<XMLS
    <attribute is_a="title"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->description) )
      {
        $xml = <<<XMLS
    <attribute is_a="description"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->status) )
      {
        $xml = <<<XMLS
    <attribute is_a="status"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->type) )
      {
        $xml = <<<XMLS
    <attribute is_a="type"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->order) )
      {
        $xml = <<<XMLS
    <attribute is_a="metaOrder"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->comment) )
      {
        $xml = <<<XMLS
    <attribute is_a="comment"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->parent) )
      {
        $xml = <<<XMLS
    <attribute is_a="parent"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

      if( isset($infos->uuid) )
      {
        $xml = <<<XMLS
    <attribute is_a="uuid"   ></attribute>
XMLS;

        $nodes[] = $this->addNode($xml, $parentNode);
      }

    }

    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionRatelist
