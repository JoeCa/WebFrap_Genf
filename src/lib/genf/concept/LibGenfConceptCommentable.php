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
class LibGenfConceptCommentable
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
        <ref name="comments" binding="connected"  relation="manyToMany" >
          <label>
            <text lang="de">Kommentare</text>
            <text lang="en">Comments</text>
          </label>
          <src        name="{$entName}"  id="vid" ></src>
          <connection name="wbfsys_entity_comment"     ></connection>
          <target     name="wbfsys_comment"   id="id_comment" ></target>

          <ui>

            <controls>
              <action name="create" status="true"  />
              <action name="connect" status="false"  />
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
