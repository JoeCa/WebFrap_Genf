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
 * Node Klasse für die BDL Projekt Beschreibung
 *
 * Soll die falsch positionierte Logik in LibGenfBuild für den Zugriff
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeProject
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @var array
   */
  protected $vars = array();

/*//////////////////////////////////////////////////////////////////////////////
// init logic
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * laden der Variablen
   */
  protected function loadChilds()
  {

    $this->exctractVars( $this->node );

  }//end protected function loadChilds */

/*//////////////////////////////////////////////////////////////////////////////
// getter methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * Das Mainpackage für Projekte mit Namespace
   * @return string
   */
  public function getMainPackage()
  {

    return isset($this->node->package)
      ? $this->replaceVars(trim($this->node->package))
      : null;

  }//end public function getMainPackage */

  /**
   * Den Key / Codenamen des Projektes auslesen.
   * Muss Cname Format haben
   * @return string
   */
  public function getKey()
  {

    return isset($this->node->key)
      ? $this->replaceVars(trim($this->node->key))
      : null;

  }//end public function getKey */

  /**
   * @return string
   */
  public function getAuthor()
  {

    return isset($this->node->author)
      ? $this->replaceVars(trim($this->node->author))
      : null;

  }//end public function getAuthor */



/*//////////////////////////////////////////////////////////////////////////////
// Variable Komponenten
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * parse the variables from the project description
   * @param SimpleXmlElement $projectXml
   * @return void
   */
  public function exctractVars( $projectXml )
  {

    // zuerst leeren
    $this->vars = array();

    // replace most important path vars
    foreach( $projectXml->var as $var )
    {
      $this->vars['{$'.trim($var['name']).'}'] = str_replace
      (
        array('{$PATH_FW}','{$PATH_GW}','{$PATH_ROOT}'),
        array(PATH_FW,PATH_GW,PATH_ROOT),
        trim($var['value'])
      );
    }

    // add most important path vars
    $this->vars['{$PATH_FW}']     = PATH_FW;
    $this->vars['{$PATH_GW}']     = PATH_GW;
    $this->vars['{$PATH_ROOT}']   = PATH_ROOT;


  }//end public function exctractVars */

  /**
   * add special vars to the project
   */
  public function addProjectVars( )
  {

    $list = array
    (
      'author',
      'copyright',
      'licence',
      'version',
      'revision',
      'arch',
      'archVersion',
      'model',
      'modelVersion',
      'projectKey',
      'projectName',
      'projectTitle',
      'projectUrl',
      'pathOutput',
      'langDefault',
      'langCode',
      'rowidKey',
    );

    foreach( $list as $var )
    {
      $this->vars["{\$$var}"] = $this->$var;
    }


  }//end public function addProjectVars */

  /**
   * get a variable from the project description
   * @param string $key
   * @return string the project variable
   */
  public function getVar( $key )
  {
     return isset( $this->vars['{$'.$key.'}'] ) ?   $this->vars['{$'.$key.'}'] : null;
  }//end public function getVar */

  /**
   * @param $string
   * @return string
   */
  public function replaceVars( $string )
  {
    return str_replace( array_keys($this->vars) , array_values($this->vars) , $string  );
  }//end public function replaceVars */

  /**
   * @param array $cartTypes übergabe von default cartridges
   * @return array
   */
  public function getCartridgeTypes( $cartTypes = array() )
  {

    $cartridgesRef = $this->node->cartridges;
    
    foreach( $cartridgesRef->cartridge as $cartridge )
    {
      $cartTypes[strtolower(trim($cartridge['type']))] = strtolower(trim($cartridge['type']));
    }
    
    $nodes = $this->node->nodes;
    
    foreach( $nodes->node as $node )
    {
      $cartTypes[strtolower(trim($node))] = strtolower(trim($node));
    }
    
    return $cartTypes;
    
  }//end public function getCartridgeTypes */
  
}//end class LibGenfTreeNodeProject

