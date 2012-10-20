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
class LibGenfDefinitionConnectionInfo
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

    $infos = simplexml_import_dom( $statement );
    
    $prefix = isset( $infos['prefix'] )?trim($infos['prefix']).'_':'';
    $category = isset( $infos['category'] )?trim($infos['category']):'connection';

    $all = false;
    if( !$infos->children()->count() )
      $all = true;

    $nodes = array();

    if( $all || isset($infos->url) )
    {
      $xml = <<<XMLS
      <attribute name="{$prefix}serv_url" type="text"  size="350"  >
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }

    if( $all || isset($infos->port) )
    {
      $xml = <<<XMLS
      <attribute name="{$prefix}serv_port" type="int" >
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }
    
    if( $all || isset($infos->protocol) )
    {
      $xml = <<<XMLS
      <attribute name="id_{$prefix}serv_protocol" target="daidalos_serv_protocol" is_a="type" >
        <uiElement type="selectbox" />
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }

    if( $all || isset($infos->user) )
    {
      $xml = <<<XMLS
      <attribute name="{$prefix}serv_username" type="text" size="120"   >
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }

    if( $all || isset($infos->passwd) )
    {
      $xml = <<<XMLS
      <attribute name="{$prefix}serv_passwd" type="text" size="64"   >
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }
    
    // ok was mein source nochmal?
    if( $all || isset($infos->source) )
    {
      $xml = <<<XMLS
      <attribute name="{$prefix}serv_source" type="text" size="128"   >
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }
    
    // namespace kann zb das schema einer datenbank sein
    if( isset($infos->namespace) )
    {
      $xml = <<<XMLS
      <attribute name="{$prefix}serv_namespace" type="text" size="128"   >
        <categories main="{$category}" />
      </attribute>
XMLS;
      $nodes[] = $this->addNode($xml, $parentNode);
    }
    
    // remove statement
    $this->remove( $statement );

    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionConnectionInfo
