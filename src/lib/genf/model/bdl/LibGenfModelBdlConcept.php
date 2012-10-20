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
class LibGenfModelBdlConcept
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  protected $content = null;

  /**
   * @param SimpleXmlElement $node
   */
  public function __construct( $node = null, $content = null )
  {

    if( $node )
      $this->import( $node );

    if( $content )
      $this->content = $content;

  }//end public function __construct */

  /**
   *
   */
  public function parse()
  {

    if( $this->content )
      return $this->content;
    else
      return parent::parse();

  }//end public function parse */

}//end class LibGenfModelBdlConcept

