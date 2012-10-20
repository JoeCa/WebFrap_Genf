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
class LibGenfDefinitionBudget
  extends LibGenfDefinitionMoney
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return [DOMNode]
   */
  public function interpret( $statement, $parentNode )
  {

    $replaced = array();
    
    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'budget';
      $vars->size       = '30.6';
      $vars->type       = 'numeric';

      $vars->label->de  = 'Budget';
      $vars->label->en  = 'Budget';
      $vars->label->fr  = 'Etat';
      $vars->label->it  = 'Budget';
      $vars->label->es  = 'Presupuesto';

      $vars->description->de  = 'Budget für '.$vars->entity->label->de;
      $vars->description->en  = 'Budget for '.$vars->entity->label->en;
      $vars->description->fr  = 'Etat pour '.$vars->entity->label->fr;
      $vars->description->it  = 'Budget per '.$vars->entity->label->it;
      $vars->description->es  = 'Presupuesto para '.$vars->entity->label->es;

      $vars->uiElement->type  = 'money';
      $vars->uiElement->size->width  = 'small';

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


} // end class LibGenfDefinitionBudget
