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
class LibGenfDefinitionRating
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
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'rate';
      $vars->type       = 'numeric';
      $vars->size       = '5.2';
      $vars->minSize    = '0';
      $vars->maxSize    = '5';
      $vars->stepSize   = '0.5';

      $vars->uiElement->type    = 'ratingbar';

      $vars->label->de  = 'Bewertung';
      $vars->label->en  = 'Rating';

      $vars->description->de   = "Bewertung für {$vars->entity->label->de}";
      $vars->description->en   = "Rating for {$vars->entity->label->en}";

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionRating
