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
class LibGenfTreeNodeColorScheme
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var string
   */
  public $key = null;
  
// default
   
  /**
   * @var string
   */
  public $textColor = null;
   
  /**
   * @var string
   */
  public $bgColor = null;

  /**
   * @var string
   */
  public $borderColor = null;
  
// active
  
  /**
   * @var string
   */
  public $activeTextColor = null;
  
  /**
   * @var string
   */
  public $activeBgColor = null;
  
  /**
   * @var string
   */
  public $activeBorderColor = null;
  
// highlight
  
  /**
   * @var string
   */
  public $highlightTextColor = null;
  
  /**
   * @var string
   */
  public $highlightBgColor = null;
  
  /**
   * @var string
   */
  public $highlightBorderColor = null;

  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->key = isset( $this->node['name'] )?:trim($this->node['name']);
    
    // default
    $this->textColor = isset( $this->node['text'] )?:trim($this->node['text']);
    $this->bgColor = isset( $this->node['bg'] )?:trim($this->node['bg']);
    $this->borderColor = isset( $this->node['border'] )?:trim($this->node['border']);
    
    // active
    $this->activeTextColor = isset( $this->node->active['text'] )?:trim($this->node->active['text']);
    $this->activeTextColor = isset( $this->node->active['bg'] )?:trim($this->node->active['bg']);
    $this->activeBorderColor = isset( $this->node->active['border'] )?:trim($this->node->active['border']);
    
    // highlight
    $this->highlightTextColor = isset( $this->node->highlight['text'] )?:trim($this->node->highlight['text']);
    $this->highlightBgColor = isset( $this->node->highlight['bg'] )?:trim($this->node->highlight['bg']);
    $this->highlightBorderColor = isset( $this->node->highlight['border'] )?:trim($this->node->highlight['border']);

  }//end protected function loadChilds */


}//end class LibGenfTreeNodeAction

