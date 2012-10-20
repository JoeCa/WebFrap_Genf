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
 *
 * <category name="time"  >
 *  <layout>
 *     <col type="1/3" align="v" fill="auto" />
 *     <col type="1/3" align="v" />
 *     <col type="1/3" align="v" />
 *   </layout>
 *   <label>
 *     <text lang="en" >Time Data</text>
 *   </label>
 * </category>
 *
 */

/**
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeCategory
  extends LibGenfTreeNode
{

  /**
   *
   * @var int
   */
  const COL_TYPE      = 0;

  /**
   *
   * @var int
   */
  const COL_SIZE      = 1;

////////////////////////////////////////////////////////////////////////////////
// attribute, mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param int $defNum
   */
  public $defNum = 2;

  /**
   *
   * @var array<LibGenfTreeNodeLayoutCol>
   */
  protected $autoLayout = array();

  /**
   *
   * @var array<string:array<string,float>>
   */
  public $classTypes = array
  (
    '1'   => array( 'full'       , 1     ),
    '1/2' => array( 'half'       , 0.5   ),
    '1/3' => array( 'third'      , 0.33  ),
    '1/4' => array( 'fourth'     , 0.25  ),
    '1/5' => array( 'fifth'      , 0.2   ),
    '1/6' => array( 'sixth'      , 0.16  ),
    '2/3' => array( 'two_third'  , 0.66  ),
    '3/4' => array( 'tree_fourth', 0.75  ),
    '2/5' => array( 'two_fifth'  , 0.4   ),
    '3/5' => array( 'three_fifth', 0.6   ),
    '4/5' => array( 'four_fifth' , 0.8   ),
    '4/6' => array( 'four_sixth' , 0.66  ),
    '5/6' => array( 'five_sixth' , 0.83  ),
  );


////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////



  /**
   * @return string
   */
  public function getName()
  {
    return $this->name->name;
  }//end public function getName */

  /**
   * @return string
   */
  public function label( $lang = 'en' )
  {
    return $this->name->label;
  }//end public function label */

  /**
   *
   */
  public function isAutoCat()
  {

    return !is_string( $this->node );

  }//end public function isAutoCat */

  /**
   * @return array
   */
  public function layout()
  {
    return $this->autoLayout;
  }//end public function layout */

  /**
   * @return SimpleXmlElement
   */
  public function getLayout( )
  {
    if( isset( $this->node->layout ) )
    {
      return $this->node->layout;
    }
    else
    {
      return null;
    }
  }//end public function getLayout */

////////////////////////////////////////////////////////////////////////////////
// extract the relevant data from the category
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $params
   */
  protected function prepareNode( $params = array() )
  {

    $this->parseNames();

  }//end protected function prepareNode */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param string $name
   * @param string $label
   * @return void
   */
  protected function parseNames(  )
  {

    $this->name = new LibGenfNameDefault( $this->node );

  }//end public function parseNames */

  /**
   * @return int
   */
  public function getNumCols()
  {
    
    if( isset( $this->node->layout->col ) )
      return $this->node->layout->count();
    else if( isset( $this->node->layout['cols'] ) )
      return trim( $this->node->layout['cols'] );
    else
      return 2;
      
  }//end public function getNumCols */
  

  /**
   * @param int $size
   * @param boolean $force
   * @return array<string:array<string,float>>
   */
  public function contextLayouts( $size = 2 , $force = false )
  {

    if( $force )
    {
      $cols = $this->getDefaultCols( $size );
    }
    else if( is_string( $this->node ) )
    {
      $cols = $this->getDefaultCols( $size );
    }
    else if( isset($this->node->layout->col) )
    {

      $cols = new TArray();
      
      $numCols = $this->node->layout->count();

      foreach( $this->node->layout->col as $pos => $col )
      {
        
        $this->builder->notice( "Category layout col {$pos}" );

        $tmp              = new LibGenfTreeNodeLayoutCol();
        $tmp->name        = $pos;
        $tmp->numElements = 0;
        $tmp->elements    = array();

        if( isset( $col['type'] ) )
          $tmp->type  = trim($col['type']);
        else
          $tmp->type  = '1/'.$numCols;

        $tmp->class   = $this->classTypes[$tmp->type][0];
        $tmp->size    = $this->classTypes[$tmp->type][0];

        if( isset( $col['align'] ) )
          $tmp->align = trim( $col['align'] );
        else
          $tmp->align = 'h'; // h: horizontal v: vertical

        if( isset( $col['fill'] ) )
          $tmp->fill  = trim( $col['fill'] );
        else
          $tmp->fill  = 'auto';

        $cols[]       = $tmp;

      }

    }
    else if( isset( $this->node->layout['cols'] ) )
    {
      $cols = $this->getDefaultCols( trim( $this->node->layout['cols'] ) );
    }
    else
    {
      $cols = $this->getDefaultCols( $size );
    }

    return $cols;

  }//end protected function contextLayouts */


  /**
   * check the layout string an extract the size names
   * check if the sum of all sizes does not overflow 1 or underflow 0.95
   *
   * @param string $hLayout
   * @return array
   */
  public function checkHLayout( $hLayout )
  {

    $layout = array();
    $size   = 0;

    foreach( $hLayout as $col )
    {

      if( !isset( $this->classTypes[$col->type] ) )
      {
        $this->builder->error( 'Got invalid Layout Type: '.$col->type.$this->builder->dumpEnv() );
        continue;
      }

      $size     +=  $this->classTypes[$col->type][self::COL_SIZE];
      $layout[] =   $this->classTypes[$col->type][self::COL_TYPE];

    }//end foreach

    if( $size > 1 )
    {
      $this->builder->error( 'The Size of all colls in this layout is bigger than 1 : '.$size.$this->builder->dumpEnv() );
    }
    elseif( $size < 0.96 ) // 0.96 is the maximum valid error: 6 * sixth
    {
      $this->builder->error( 'The Size of all colls in this layout is smaller than 0.96 : '.$size.$this->builder->dumpEnv() );
    }

    return $layout;


  }//end protected function checkHLayout */

  /**
   * @param int $size
   */
  public function getDefaultCols( $size = 2 )
  {

    $method = 'getDefaultCols'.$size;

    if( method_exists( $this, $method ) )
    {
     return $this->$method( );
    }
    else
    {
      $this->builder->error( 'Requested invalid Default col size '.$size.$this->builder->dumpEnv() );
      return $this->getDefaultCols4();
    }

  }// public function getDefaultCols */

  /**
   * @return array
   */
  public function getDefaultCols1()
  {
    $cols = new TArray();

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 0;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1';
    $tmp->class       = 'full';
    $tmp->size        = 1;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    return $cols;
  }//end public function getDefaultCols1 */

  /**
   * @return array
   */
  public function getDefaultCols2()
  {

    $cols = new TArray();

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 0;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/2';
    $tmp->class       = 'half';
    $tmp->size        = 0.5;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 1;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/2';
    $tmp->class       = 'half';
    $tmp->size        = 0.5;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    return $cols;

  }//end public function getDefaultCols2 */

  /**
   * @return array
   */
  public function getDefaultCols3()
  {

    $cols = new TArray();

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 0;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/3';
    $tmp->class       = 'third';
    $tmp->size        = 0.33;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 1;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/3';
    $tmp->class       = 'third';
    $tmp->size        = 0.33;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 2;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/3';
    $tmp->class       = 'third';
    $tmp->size        = 0.33;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    return $cols;

  }//end public function getDefaultCols3 */

  
  /**
   * @return array
   */
  public function getDefaultCols4()
  {

    $cols = new TArray();

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 0;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/4';
    $tmp->class       = 'fourth';
    $tmp->size        = 0.25;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 1;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/4';
    $tmp->class       = 'fourth';
    $tmp->size        = 0.25;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 2;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/4';
    $tmp->class       = 'fourth';
    $tmp->size        = 0.25;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;
    
    $tmp              = new LibGenfTreeNodeLayoutCol();
    $tmp->name        = 3;
    $tmp->numElements = 0;
    $tmp->elements    = array();
    $tmp->type        = '1/4';
    $tmp->class       = 'fourth';
    $tmp->size        = 0.25;
    $tmp->align       = 'h';
    $tmp->fill        = 'auto';
    $cols[]           = $tmp;

    return $cols;

  }//end public function getDefaultCols4 */
  
}//end class LibGenfTreeNodeCategory

