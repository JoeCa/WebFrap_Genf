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
class LibGenfDefinitionRichContent
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

    try
    {
      
      $check  = simplexml_import_dom( $statement );
      
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'content';
      $vars->type       = 'text';
      $vars->validator  = 'html';

      $vars->uiElement->type            = 'wysiwyg';
      $vars->uiElement->position->valign  = 'bottom';
      $vars->uiElement->mode  = 'rich_text';

      $vars->search->type = 'like';

      $vars->label->de  = 'Text';
      $vars->label->en  = 'Content';

      $vars->description->de   = "Text zu {$vars->entity->label->de}";
      $vars->description->en   = "Content for {$vars->entity->label->en}";

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }



  }//end public function interpret */


} // end class LibGenfDefinitionRichContent
