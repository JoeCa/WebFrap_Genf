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
class LibGenfTreeNodeSubpage
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  protected $layout = null;

  /**
   * backreference to the owner page
   * @var LibGenfTreeNodePage
   */
  public $page = null;

////////////////////////////////////////////////////////////////////////////////
// getter + setter methode
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return string the name of the entity
   */
  public function name()
  {

    return $this->name;

  }//end public function name */

  /**
   * @return SimpleXmlElement
   */
  public function getLayout()
  {

    return isset( $this->node->layout )
      ? $this->node->layout
      : null;

  }//end public function getLayout */

  /**
   * @return array
   */
  public function getAccess()
  {

    if( !isset($this->node['access']) )
      return array('GET');

    $access = strtoupper(trim( $this->node['access'] ));

    return explode('|',$access);

  }//end public function getAccess */

  /**
   *
   * Enter description here ...
   * @param unknown_type $lang
   */
  public function getTitle( $lang = 'en' )
  {

    if( isset( $this->node->title ) )
      return $this->builder->interpreter->i18nText($this->node->title,$lang);
    else
      return $this->page->getTitle( $lang );

  }//end public function getTitle */

  /**
   *
   */
  public function getDyntextKeys()
  {

    $keys = array();

    if( !isset( $this->node->layout ) )
      return $keys;

    $dynTexts = $this->node->layout->xpath('//dyntext');

    foreach( $dynTexts as $dynText )
    {
      $keys[] = trim($dynText['key']);
    }

    return $keys;

  }//end public function getDyntextKeys */

/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDefault( $this->node );

  }//end protected function loadChilds */

}//end class LibGenfTreeNodeSubpage

