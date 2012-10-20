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
class LibGenfTreeNodePageSubpage
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
  public function getMethod()
  {

    if( !isset($this->node['method']) )
      return array('GET');

    $access = strtoupper(trim( $this->node['method'] ));

    return explode( '|', $access );

  }//end public function getMethod */

  /**
   *
   * @param string $lang
   */
  public function getTitle( $lang = 'en' )
  {

    if( isset( $this->node->title ) )
      return $this->builder->interpreter->i18nText($this->node->title,$lang);
    else
      return $this->page->getTitle( $lang );

  }//end public function getTitle */

  /**
   * @return string
   */
  public function getMaster()
  {

    return isset( $this->node->master )
      ? $this->node->master
      : null;

  }//end public function getMaster */
  
  /**
   * @return array
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
  
  /**
   * @return array
   */
  public function getCmsTextKeys()
  {

    $keys = array();

    if( !isset( $this->node->layout ) )
      return $keys;

    $dynTexts = $this->node->layout->xpath('//cms_text');

    foreach( $dynTexts as $dynText )
    {
      $keys[] = trim($dynText['key']);
    }

    return $keys;

  }//end public function getCmsTextKeys */

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

