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
class LibGenfConceptTagable
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

    $markup = <<<BDL

    <entity name="{$entName}" >
      <references>
        <ref name="tags" binding="free"  relation="manyToMany" >
        
          <label>
            <text lang="de">Tags</text>
            <text lang="en">Tags</text>
          </label>
          
          <src        name="{$entName}" id="vid" ></src>
          <connection name="wbfsys_entity_tag"     ></connection>
          <target     name="wbfsys_tag"  id="id_tag" ></target>

          <ui>
            <controls>
              <action name="create"   status="true"  />
              <action name="connect"  status="true"  />
            </controls>

            <list>
              <table>
                <footer type="simple" />
              </table>
            </list>
          </ui>

        </ref>
      </references>
    </entity>

BDL;

    $this->addEntity($markup);

    return array();

  }//end public function interpret */


} // end class LibGenfConceptCommentable
