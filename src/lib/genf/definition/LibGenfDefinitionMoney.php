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
class LibGenfDefinitionMoney
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
    
    $replaced = array();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'cost';
      $vars->type       = 'numeric';
      $vars->size       = '30.6';

      $vars->label->de  = 'Kosten';
      $vars->label->en  = 'Cost';

      $vars->description->de  = 'Kosten für '.$vars->entity->label->de;
      $vars->description->en  = 'Cost for '.$vars->entity->label->en;

      $vars->categories->main         = 'budget';
      $vars->uiElement->type          = 'money';
      $vars->uiElement->size->width   = 'small';


      //$xml = $vars->parse();

      $replaced[] = $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }
    
    $simpelNode  = simplexml_import_dom( $statement);
    
    if( isset( $simpelNode->currency ) )
    {
      $xml = <<<XMLS

      <attribute name="id_{$vars->name}_currency" target="wbfsys_currency"   >
        <uiElement type="selectbox" >
          <position relation="below" target="{$vars->name}" />
        </uiElement>
      </attribute>

XMLS;

      $replaced[] = $this->addNode($xml, $parentNode);
    }

    return $replaced;

  }//end public function interpret */


} // end class LibGenfDefinitionMoney
