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
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfConceptTree
  extends LibGenfConcept
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $conceptNode
   * @return boolean
   */
  public function interpret( $conceptNode )
  {

    $entity   = $this->getMainNode( $conceptNode );
    $entName  = $entity->getAttribute('name');

    $sNode = simplexml_import_dom($conceptNode);

    $keyName = isset( $sNode->field['name'] )
      ? trim($sNode->field['name'])
      : 'm_parent';

    $markup = <<<BDL

    <entity name="{$entName}" >
      <attributes>
        <attribute name="{$keyName}" target="{$entName}" >
          <label>
            <text lang="de" >Vaterknoten</text>
            <text lang="en" >Parent Node</text>
          </label>
        </attribute>
      </attributes>
    </entity>

BDL;

    $this->addEntity( $markup );

    return array();

  }//end public function interpret */


} // end class LibGenfConceptTree
