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
class LibGenfDefinitionNestedSet
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    $vars = $this->checkAttribute( $statement, $parentNode  );


    $vars->uiElement->type  = 'hidden';

    $list = array();

    $xml = <<<XMLS
      <attribute name="lft" type="int"  >

        {$vars->uiElement}

      </attribute>
XMLS;

    $list[] = $this->replaceDefinition($xml, $statement, $parentNode);

    $xml = <<<XMLS
      <attribute name="rgt" type="int"  >

        {$vars->uiElement}

      </attribute>
XMLS;


    $list[] = $this->addNode($xml,$parentNode);

    return $list;

  }//end public function interpret */


} // end class LibGenfDefinitionNestedSet
