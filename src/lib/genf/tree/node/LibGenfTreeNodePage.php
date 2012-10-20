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
class LibGenfTreeNodePage
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfTreeNodeUi
   */
  public $ui = null;

  /**
   * @var array<LibGenfTreeNodePageSubpage>
   */
  protected $subpages = array();
  
  /**
   *
   * @var array<LibGenfTreeNodePageMaster>
   */
  protected $masterTemplates = array();

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

    // only exists if subnode exists
    if( isset($this->node->ui) )
    {
      $uiClassName      = $this->builder->getNodeClass('Ui');
      $this->ui         = new $uiClassName( $this->node->ui );
    }

    // only exists if subnode exists
    if( isset($this->node->subpage ) )
    {
      foreach( $this->node->subpage as $subpage )
      {
        $key = trim($subpage['name']);

        $this->subpages[$key] = new LibGenfTreeNodePageSubpage($subpage);
        $this->subpages[$key]->page = $this;

      }
    }
    
      // only exists if subnode exists
    if( isset($this->node->master_template ) )
    {

      foreach( $this->node->master_template as $masterTemplate )
      {
        $key = trim($masterTemplate['name']);

        $this->masterTemplates[$key] = new LibGenfTreeNodePageMaster($masterTemplate);
      }
    }

  }//end protected function loadChilds */

////////////////////////////////////////////////////////////////////////////////
// getter + setter methode
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @return string the name of the entity
   */
  public function type()
  {
    return trim( $this->node['type'] );
  }//end public function type */

  /**
   * @return array<LibGenfTreeNodeSubpage>
   */
  public function getSubPages()
  {
    return $this->subpages;
  }//end public function getSubPages */

  /**
   * @return array<LibGenfTreeNodePageMaster>
   */
  public function getMasters()
  {
    return $this->masterTemplates;
  }//end public function getMasters */

  /**
   *
   * @param string $lang
   * @return string
   */
  public function getTitle( $lang = 'en' )
  {

    if( isset( $this->node->title ) )
      return $this->builder->interpreter->i18nText($this->node->title,$lang);
    else
      return $this->builder->getTitle( $lang );

  }//end public function getTitle */





}//end class LibGenfTreeNodePage

