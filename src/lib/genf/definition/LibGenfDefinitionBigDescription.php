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
class LibGenfDefinitionBigDescription
  extends LibGenfDefinition
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

    $definition = simplexml_import_dom( $statement );
    
    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'description';
      $vars->type       = 'text';
      $vars->validator  = 'text';

      $vars->label->de  = 'Beschreibung';
      $vars->label->en  = 'Description';

      $vars->description->de  = 'Beschreibung für '.$vars->entity->label->de;
      $vars->description->en  = 'Description for '.$vars->entity->label->en;

      $vars->categories->main  = 'description';

      $vars->concepts->addConcept( 'i18n', new LibGenfModelBdlConcept( null, '<concept name="i18n" />' ) );

      $vars->uiElement->type              = 'textarea';
      $vars->uiElement->position->priority  = '5';
      $vars->uiElement->position->valign    = 'bottom';
    
      if( isset( $statement->size ) )
      {
        
        if( isset( $statement->size['width'] ) )
        {
          $vars->uiElement->size->width   = trim($statement->size['width']);
        }
        else
        {
          $vars->uiElement->size->width   = "full";
        }
        
        if( isset( $statement->size['height'] ) )
        {
          $vars->uiElement->size->height  = trim($statement->size['height']);
        }
        else
        {
          $vars->uiElement->size->height  = "large";
        }
        
      }
      else
      {
        $vars->uiElement->size->width   = "full";
        $vars->uiElement->size->height  = "large";
      }

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


}//end class LibGenfDefinitionDescription
