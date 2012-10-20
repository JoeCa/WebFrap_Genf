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
class LibGenfDefinitionMetaArchive
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @param SimpleXmlElement $parentNode
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'm_archive';
      $vars->type       = 'date';

      $vars->label->de  = 'Archiv';
      $vars->label->en  = 'archiv';

      $vars->description->de  = 'Status ab wann dieser Eintrag archiviert ist';
      $vars->description->en  = 'State sinse when this entry is archived';

      $vars->categories->main  = 'meta';


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionMetaArchive
