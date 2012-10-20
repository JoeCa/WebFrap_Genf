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
class LibGenfDefinitionIcon
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return [DOMNode]
   */
  public function interpret( $statement, $parentNode )
  {

    $nodes = array();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'icon';
      $vars->size       = '250';
      $vars->type       = 'text';
      $vars->validator  = 'file';

      $vars->uiElement->type  = 'FileImage';

      $vars->label->de  = 'Icon';
      $vars->label->en  = 'Icon';

      $vars->description->de  = 'Icon für '.$vars->entity->label->de;
      $vars->description->en  = 'Icon for '.$vars->entity->label->en;

      $nodes[] = $this->replaceDefinition($vars->parse(), $statement, $parentNode);

      $xml = <<<XMLS
    <attribute name="mimetype" type="text" size="20"   >
      <uiElement type="hidden" />
    </attribute>
XMLS;

    $nodes[] = $this->addNode($xml, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    return $nodes;

  }//end public function interpret */



} // end class LibGenfDefinitionIcon
