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
class LibGenfDefinitionImage
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return LibGenfModelBdlAttribute
   */
  public function interpret( $statement, $parentNode )
  {

    $nodes = array();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'image';
      $vars->size       = '250';
      $vars->type       = 'text';
      $vars->validator  = 'image';

      $vars->uiElement->type  = 'FileImage';
      
      
      $vars->uiElement->position->priority  = '20';

      $nodes[] = $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    $xml = <<<XMLS
    <attribute name="mimetype" type="text" size="20"   >
      <uiElement type="hidden" />
    </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    return $nodes;

  }//end public function interpret */


} // end class LibGenfDefinitionImage
