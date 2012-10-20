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
class LibGenfConceptI18n
  extends LibGenfConcept
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $conceptNode )
  {

    $type = $this->parentType( $conceptNode );

    switch( $type )
    {
      case 'entity':
      {
        return $this->interpretEntity( $conceptNode );
        break;
      }
      case 'category':
      {
        // makes no real sense
        return $this->interpretCategory( $conceptNode );
        break;
      }
      case 'attribute':
      {
        return $this->interpretAttribute( $conceptNode );
        break;
      }
    }

    return array();


  }//end public function interpret */

  /**
   * @param DOMNode $conceptNode
   */
  public function interpretEntity( $conceptNode )
  {

    $entity = $this->getParent( $conceptNode );
    //$entityObj = $this->getEntity( $entity->getAttribute('name') );

    $xQuery = './attributes/attribute/concepts/concept[@name="i18n"]';

    $xPathObj = new DOMXPath( $entity->ownerDocument );

    $attrList =  $xPathObj->query( $xQuery, $entity );

    if( !$attrList->length )
      return array();

    $i18nAttributes = array();

    foreach( $attrList as $attrConcept )
    {

      $attr = $attrConcept->parentNode->parentNode;
      $attrConcept->setAttribute('interpreted','true');

      $i18nAttributes[] = simplexml_import_dom($attr)->asXML();

    }

    return $this->addI18nEntity( $entity, $i18nAttributes );


  }//end public function interpretEntity */

  /**
   * @param DOMNode $conceptNode
   */
  public function addI18nEntity( $entity, $i18nAttributes )
  {

    $simpleEntity = simplexml_import_dom($entity);


    $i18nAttrCode = implode( NL, $i18nAttributes );

    $xml = <<<XML

    <entity name="{$simpleEntity['name']}_i18n" type="meta" >


      <description>
        <text lang="de" >I18N Hilfstabelle für die Tabelle {$simpleEntity['name']}</text>
      </description>

      <ui>
        <global>
          <menutree type="disabled" />
        </global>
      </ui>

      <attributes>

        <attribute name="id_lang" target="wbfsys_language" />
        <attribute name="id_{$simpleEntity['name']}" target="{$simpleEntity['name']}" />

{$i18nAttrCode}

      </attributes>

    </entity>

XML;

    return $this->addEntity( $xml );

  }//end public function addI18nEntity */


  /**
   * @param DOMNode $conceptNode
   */
  public function interpretCategory( $conceptNode )
  {

    return array();
  }//end public function interpretCategory */


  /**
   * @param DOMNode $conceptNode
   */
  public function interpretAttribute( $conceptNode )
  {
    return array();
  }//end public function interpretAttribute */




} // end class LibGenfConceptI18n
