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
class LibGenfConceptOwnership
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
  
    /*
    $type = $this->parentType( $conceptNode );

    switch( $type )
    {
      case 'entity':
      {
        return $this->interpretEntity( $conceptNode );
        break;
      }
    }
    */

    return array();
    

  }//end public function interpret */

  /**
   * @param DOMNode $conceptNode
   */
  public function interpretEntity( $conceptNode )
  {
    
    /*
    $entity = $this->getParent( $conceptNode );
    $simpleEntity = simplexml_import_dom($entity);

    $xml = <<<XML

    <entity name="{$simpleEntity['name']}"  >

      <attributes>
        <attribute is_a="route" />
      </attributes>

    </entity>

XML;

    return $this->addEntity( $xml );
    */

  }//end public function interpretEntity */



} // end class LibGenfConceptOwnership
