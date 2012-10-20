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
class LibGenfTreeNodePageMaster
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  protected $layout = null;

////////////////////////////////////////////////////////////////////////////////
// getter + setter methode
////////////////////////////////////////////////////////////////////////////////


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
  
  /**
   *
   */
  public function getCmsTexts()
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

  }//end public function getCmsTexts */

/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameMin( $this->node );

  }//end protected function loadChilds */

}//end class LibGenfTreeNodeSubpage

