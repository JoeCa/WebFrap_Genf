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
class LibGenfConceptTrack
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
    
    $entity = $this->getEntity( $entity->getAttribute('name') );

    $xQuery = './attributes/attribute/concepts/concept[@name="track"]';

    $attrList =  $this->builder->xpath( $xQuery, $entity );

    if( !$attrList->length )
      return array();

    $trackAttributes = array();

    foreach( $attrList as $attrConcept )
    {
      
      $attrConcept->setAttribute('interpreted','true');
      $attr = $attrConcept->parentNode->parentNode;
      
      $key = $attrConcept->getAttribute('key');
      
      $trackAttributes[$key] = simplexml_import_dom($attr)->asXML();

    }

    return $this->addTrackerEntity( $entity, $trackAttributes );


  }//end public function interpretEntity */




  /**
   * @param DOMNode $conceptNode
   */
  public function interpretCategory( $conceptNode )
  {
    
    $categoryName = $conceptNode->getAttribute('name');
    
    if( $conceptNode->hasAttribute('key')  )
    {
      $categoryKey = $conceptNode->getAttribute('key');
    }
    else 
    {
      $categoryKey = $conceptNode->getAttribute('name');
    }

    $entity = $this->getParent( $conceptNode )->parentNode;
    $entity = $this->getEntity( $entity->getAttribute('name') );

    $xQuery = './attributes/attribute/categories[@main="'.$categoryName.'"]';

    $attrList =  $this->builder->xpath( $xQuery, $entity );

    if( !$attrList->length )
      return array();

    $trackAttributes = array();

    foreach( $attrList as $attrConcept )
    {
      
      $attrConcept->setAttribute('interpreted','true');
      $attr = $attrConcept->parentNode->parentNode;
    
      $trackAttributes[$categoryKey] = simplexml_import_dom($attr)->asXML();

    }

    return $this->addTrackerEntity( $entity, $trackAttributes );

  }//end public function interpretCategory */


  /**
   * @param DOMNode $conceptNode
   */
  public function interpretAttribute( $conceptNode )
  {

    return array();
  }//end public function interpretAttribute */


  /**
   * @param DOMNode $conceptNode
   */
  public function addTrackerEntity( $entity, $trackAttributes )
  {

    $simpleEntity = simplexml_import_dom($entity);
  
    $entities = array();
    
    foreach( $trackAttributes as $key => $attrData )
    {

      $trackAttrCode = implode( NL, $attrData );
      
      $codeKey = strtolower($key);

    $xml = <<<XML

    <entity name="{$simpleEntity['name']}_{$codeKey}_track" type="meta" >

      <description>
        <text lang="de" >Tracker Hilfstabelle für die Tabelle {$simpleEntity['name']}</text>
      </description>

      <ui>
        <global>
          <menutree type="disabled" />
        </global>
      </ui>

      <attributes>

        <attribute name="id_{$simpleEntity['name']}" target="{$simpleEntity['name']}" />

{$trackAttrCode}

      </attributes>

    </entity>

XML;

      $entities[] = $this->addEntity( $xml );
    
    }//end foreach
    
    return $entities;

  }//end public function addTrackerEntity */

} // end class LibGenfConceptI18n
