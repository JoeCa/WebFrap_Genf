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
 * @subpackage WebFrap
 *
 */
class RecordBdlProject
  extends RecordAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Public Record Attributes
////////////////////////////////////////////////////////////////////////////////

  public $var = array();

  public $author = null;

  public $copyright = null;

  public $licence = null;

  public $adminMail = null;

  public $lang = null;

  public $country = null;

  public $timezone = null;

  public $pre_login_module = null;

  public $post_login_module = null;

  public $login_module = null;

  public $projectTemplate  = null;

  public $databases  = array();

  public $imports = array();

////////////////////////////////////////////////////////////////////////////////
// Projected Attributes
////////////////////////////////////////////////////////////////////////////////

  protected $fileName = null;

  protected $projectXml = null;

  protected $fullTree = null;

  protected $files = null;

////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return unknown_type
   */
  public function __construct( $id = null )
  {

    if( $id )
      $this->load( $id );

  }//end public function __construct

////////////////////////////////////////////////////////////////////////////////
// Implement from Abstract Parent
////////////////////////////////////////////////////////////////////////////////

  public function persist()
  {

  }//end public function persist()

  public function remove()
  {

  }//end public function persist()

  /**
   * (non-PHPdoc)
   * @see src/record/RecordAbstract#load()
   */
  public function load( $id )
  {

    $this->projectXml = new SimpleXMLElement( $id );

    $this->author     = trim($this->projectXml->author);
    $this->copyright  = trim($this->projectXml->copyright);
    $this->licence    = trim($this->projectXml->licence);
    $this->adminMail  = trim($this->projectXml->adminMail);
    $this->lang       = trim($this->projectXml->lang);
    $this->country    = trim($this->projectXml->country);
    $this->timezone   = trim($this->projectXml->timezone);
    $this->pre_login_module   = trim($this->projectXml->pre_login_module);
    $this->post_login_module  = trim($this->projectXml->post_login_module);
    $this->login_module       = trim($this->projectXml->login_module);
    $this->projectTemplate    = trim($this->projectXml->projectTemplate);

  }//end public function load()

  public function init( $id )
  {

  }//end public function init()

}//end class RecordProcessPlow



