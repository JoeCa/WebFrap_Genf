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
 * Mit ACLs können Berechtigungen auf Entitäten oder Datensätze gelegt werden
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfConceptNestedSet
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

    $entity = $this->getMainNode( $conceptNode );

    $entName = $entity->getAttribute('name');

    $markup = <<<BDL

    <entity name="{$entName}" >
      <attributes>
        <attribute name="m_root" type="int" validator="eid"  >
          <categories main="meta" />
        </attribute>
        <attribute name="m_node_lft" type="int" validator="eid"  >
          <categories main="meta" />
        </attribute>
        <attribute name="m_node_rgt" type="int" validator="eid"  >
          <categories main="meta" />
        </attribute>
      </attributes>
    </entity>

BDL;

    $this->addEntity($markup);

    return array();

  }//end public function interpret */


} // end class LibGenfConceptNestedSet
