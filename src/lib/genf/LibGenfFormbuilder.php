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
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfFormbuilder
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * the activ environment
   * @var LibGenfEnvManagement
   */
  public $env            = null;

  /**
   *
   * @var array
   */
  public $catIndex       = array();

  /**
   *
   * @var array
   */
  public $attrIndex      = array();

  /**
   *
   * @var array
   */
  public $categories     = array();

  /**
   *
   * @var int
   */
  public $defColNumbers  = 2;


  /**
   *
   * @var LibGenfTreenodeManagement
   */
  public $management      = null;


  /**
   *
   * @var array
   */
  public $managementIndex = array();

  /**
   *
   * @var array
   */
  protected $singleCol    = array
  (
    'top',
    'bottom'
  );

////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfEnv $env
   * @param string $context
   */
  public function __construct( $env = null, $context = null )
  {

  }//end public function __construct */


////////////////////////////////////////////////////////////////////////////////
// add managements
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * Enter description here ...
   * @param LibGenfTreeNodeManagement $management
   */
  public function setManagement( $management  )
  {
    $this->management = $management;
  }//end public function setMagnagement */


  /**
   * check if the entity is allready loaded
   * @param string $key
   * @return boolean
   */
  public function managementLoaded( $key )
  {
    return isset($this->managementIndex[$key]);
  }//end public function managementLoaded */

  /**
   * @param LibGenfTreenodeManagement $management
   */
  public function addManagement( $management, $alias = null, $categories = null, $ref = null )
  {

    if( !$alias )
     $alias = $management->name->name;

    $this->managementIndex[$alias] = $alias;

    // get all categories

    $allCategories = $management->entity->getCategories();

    foreach( $allCategories as $category )
    {

      $catName = $category->getName();

      if( is_array( $categories ) )
      {
        if( !in_array( $catName, $categories ) )
        {
          continue;
        }
      }

      if( !isset( $this->categories[$catName] ) )
      {
        $this->categories[$catName] = $category;
      }
      else
      {
        // only overwrite if the old is a halfcat
        if( $this->categories[$catName]->isAutoCat() )
          $this->categories[$catName] = $category;
      }

    }//end foreach( $categories as $category )

    foreach( $management->entity as $attribute )
    {

      $categoryName = $attribute->mainCategory();

      if( is_array( $categories ) )
      {
        if( !in_array( $categoryName, $categories ) )
        {
          continue;
        }
      }

      $envelop      = new LibGenfEnvelopFormattribute( $attribute, $management );

      if( $ref )
        $envelop->ref = $ref;

      if( !isset( $this->catIndex[$categoryName] ) )
        $this->catIndex[$categoryName] = array();

      $this->catIndex[$categoryName][]        = $envelop;
      $this->attrIndex[$envelop->fullName()]  = $envelop;

    }//end foreach( $entity as $attribute )

  }//end public function addManagement */

  /**
   * @param LibGenfTreenodeManagement $management
   * @param array $categories
   */
  public function addCompleteManagement( $management, $categories = null  )
  {

    $this->management = $management;
    $this->addManagement( $management, null, $categories );

    $refs = $management->getSingleRefs();

    foreach( $refs as $ref )
    {
      $this->addManagement( $ref->targetManagement(), null, $categories, $ref );
    }

  }//end public function addCompleteManagement */
  
  /**
   * @param LibGenfEnvManagement $env
   */
  public function addSearchEnv( $env )
  {
    
    $this->management = $env->management;

    // get all categories
    $allCategories = $this->buildCustomAttributes( $env );

    foreach( $env->searchFields as $searchAttributes )
    {
      
      foreach( $searchAttributes as $contextAttr )
      {
        
        $attribute = $contextAttr->unpack();
  
        $categoryName = $attribute->mainCategory();
        $envelop      = new LibGenfEnvelopFormattribute( $attribute, $contextAttr->management );
        

        if( $contextAttr->ref )
          $envelop->ref = $contextAttr->ref;
        
  
        if( !isset( $this->catIndex[$categoryName] ) )
          $this->catIndex[$categoryName] = array();
  
        $this->catIndex[$categoryName][$envelop->fullName()] = $envelop;
        $this->attrIndex[$envelop->fullName()]  = $envelop;
  
      }//end foreach 
      
    }
    


  }//end public function addSearchEnv */
  
  /**
   * @param LibGenfEnvManagement $env
   * 
   * @return array
   */
  protected function buildCustomAttributes( $env )
  {
    
    $categories     = new TArray();

    foreach( $env->searchFields as $fields )
    {
      foreach( $fields as $field )
      {
        $catName = (string)$field->categories['main'];
        
        if( !isset( $this->categories[$catName] ) )
          $categories[$catName] = new LibGenfTreeNodeCategory( $catName );
        
      }
    }
    
    $this->categories = new TArray();
    
    foreach( $categories as $category )
    {

      $catName = $category->getName();

      if( !isset($this->categories[$catName]) )
      {
        $this->categories[$catName] = $category;
      }
      else
      {
        // only overwrite if the old is a halfcat
        if( $this->categories[$catName]->isAutoCat() )
          $this->categories[$catName] = $category;
      }

    }//end foreach( $categories as $category )
    
    
    return $categories;

  }//end protected function buildCustomAttributes */


  /**
   * @param LibGenfTreenodeEntity $entity
   */
  public function addCompleteSearchManagement( $management, $categories = null )
  {

    $this->management = $management;
    $this->addSearchManagement( $management, $categories );

    $refs = $management->getSingleRefs();

    foreach( $refs as $ref )
    {
      $this->addSearchManagement( $ref->targetManagement(), $categories, $ref  );
    }

  }//end public function addCompleteSearchManagement */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array $categories
   */
  public function addSearchManagement( $management, $categories = null, $ref = null )
  {

    // get all categories
    $allCategories = $management->entity->getCategories();

    foreach( $allCategories as $category )
    {

      $catName = $category->getName();

      if(is_array($categories))
      {
        if(!in_array( $catName, $categories ))
        {
          continue;
        }
      }

      if( !isset($this->categories[$catName]) )
      {
        $this->categories[$catName] = $category;
      }
      else
      {
        // only overwrite if the old is a halfcat
        if( $this->categories[$catName]->isAutoCat() )
          $this->categories[$catName] = $category;
      }

    }//end foreach( $categories as $category )

    $searchAttributes = $management->entity->getSearchAttributes();

    foreach( $searchAttributes as $attribute )
    {

      $categoryName = $attribute->mainCategory();
      $envelop      = new LibGenfEnvelopFormattribute( $attribute, $management );

      if( $ref )
        $envelop->ref = $ref;

      if(!isset($this->catIndex[$categoryName]))
        $this->catIndex[$categoryName] = array();

      $this->catIndex[$categoryName][$envelop->fullName()] = $envelop;
      $this->attrIndex[$envelop->fullName()]  = $envelop;

    }//end foreach( $entity as $attribute )

  }//end public function addSearchManagement */


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array<LibGenfTreeNodeCategory> $categories
   */
  public function getCategories( $categories = null )
  {

    if( is_null( $categories ) )
      return $this->categories;

    $tmpCat = array();

    foreach( $categories as $category )
    {
      if( isset( $this->categories[$category] ) )
        $tmpCat[$category] = $this->categories[$category];
    }

    return $tmpCat;

  }//end public function getCategories */

  /**
   * 
   * @param string $catName
   * @param int $numCols 
   * @param boolean $forceColSize
   * @return array
   */
  public function getCategoryAttributes( $catName, $numCols = 2, $forceColSize = false )
  {

    $preMatrix        = array();
    $appended         = array();
    $hiddenAttributes = array();

    // empty can happen if there is a given category but no attributes in it
    $attributes       = isset( $this->catIndex[$catName] )
      ? $this->catIndex[$catName]
      : array();

    $category         = $this->categories[$catName];
    $category->defNum = $this->defColNumbers;

    // valign     top / middle / bottom
    // align      1 ... X / auto
    // priority   intvalue
    // relation   leftOf / rightOf / under / above
    // target     name of an attribute

    // all elements that should be appended automatically to a col
    $autoAppend = new TArray();
    
    // die tatsächliche anzahl spalten des category layouts
    $categoryNumCols = $category->getNumCols();
  
    $layout     = $category->contextLayouts();
    $autoLayout = $this->getCategoryLayout( $category, $numCols, $forceColSize );

    // assign all attributes
    foreach( $attributes as $attribute )
    {

      $uiPos  = $attribute->uiElement->position();
      $fName  = $attribute->fullName();

      // bereits vorhandenen attribute einfach ignorieren
      if( isset( $appended[$fName] ) )
        continue;
      else
        $appended[$fName] = true;


      // attribute ist versteck
      if( $attribute->hidden( ) )
      {
        $hiddenAttributes[] = $attribute;
        continue;
      }


      // if parent attribut exists, append, else add normal in the matrix
      if( $uiPos->relation && isset( $this->attrIndex[$uiPos->target] )  )
      {

        $this->attrIndex[$uiPos->target]->addChild( $attribute );

        // if the attribute is allready assigned append the weight
        // if not the weight will be appendend on the assignment
        if( !is_null($this->attrIndex[$uiPos->target]->layoutCol) )
          $this->attrIndex[$uiPos->target]->layoutCol->weight += $attribute->weight();

      }//end if( $uiPos->relation )
      else
      {

        // erstellen eines neuen vertikalen stacks wenn noch nicht vorhanden
        if( !isset( $preMatrix[$uiPos->valign] ) )
          $preMatrix[$uiPos->valign] = array();

        // prüfen ob es nur eine spalte in dem abschnitt gibt
        if( in_array( $uiPos->valign , $this->singleCol ) )
        {
          // create subfields if not yet exist
          if( !isset( $preMatrix[$uiPos->valign][(int)$uiPos->priority] ) )
            $preMatrix[$uiPos->valign][(int)$uiPos->priority] = array();

          $preMatrix[$uiPos->valign][(int)$uiPos->priority][$fName]  =  $attribute;
        }
        else
        {
          
          $autoAppend->append( $attribute );
        
          /*
          // if auto or the col not exists autoappend the attribute later
          if( 'auto' == $uiPos->align )
          {
            $autoAppend->append( $attribute );
          }
          // if this attribute maps to a auto asign
          else if( isset( $autoLayout[$uiPos->align] ) )
          {

            // hack für __get __set
            $elements   = $autoLayout[$uiPos->align]->elements;
            $elements[] = $attribute;
            $autoLayout[$uiPos->align]->elements = $elements;

            $autoLayout[$uiPos->align]->weight += $attribute->weight();
            $attribute->layoutCol = $autoLayout[$uiPos->align];

          }
          else // only cols that are not filled up automatically
          {
            // create subfields if not yet exist
            if( !isset( $preMatrix[$uiPos->valign][$uiPos->align] ) )
              $preMatrix[$uiPos->valign][$uiPos->align] = array();

            if( !isset( $preMatrix[$uiPos->valign][$uiPos->align][(int)$uiPos->priority] ) )
              $preMatrix[$uiPos->valign][$uiPos->align][(int)$uiPos->priority] = array();

            $preMatrix[$uiPos->valign][$uiPos->align][(int)$uiPos->priority][$fName] =  $attribute;

          }
          */

        }//end else

      }//end else

    }//end foreach( $attributes as $attribute )

    // append the hidden attributes
    if( $hiddenAttributes )
      $preMatrix['hidden'] = $hiddenAttributes;

    // if only one, don't thin just do
    if( 1 === count( $autoLayout ) )
    {

      // get the correct key pos, could be that there is more than one row
      // but the others are marked as not autofillup able
      $colPos = key( $autoLayout );

      // create subfields if not yet exist
      if( !isset( $preMatrix['middle'] ) )
        $preMatrix['middle'] = array();

      if( !isset( $preMatrix['middle'][$colPos] ) )
        $preMatrix['middle'][$colPos] = array();

      foreach( $autoAppend as $attribute )
      {
        $uiPos = $attribute->uiElement->position();
        $fName = $attribute->fullName();

        if( !isset( $preMatrix['middle'][$colPos][(int)$uiPos->priority] ) )
          $preMatrix['middle'][$colPos][(int)$uiPos->priority] = array();

        $preMatrix['middle'][$colPos][(int)$uiPos->priority][$fName] = $attribute;
      }

    }
    else
    {

      // ok now we have a little problem
      // we have x stacks, with pontential different stacksizes of ui elements
      // an we have a number y of auto appended ui elements
      // now we have to fill upp all stacks, goal ist to fill up them as
      // consistent as possible
      // strategy is to fill up the smallest first until all stacks are equal
      // after that the rest of the entries is added equally until the auto stack
      // is empty
      $balancedStacks = $this->fillUpStacks( $autoLayout , $autoAppend );

      // add vals if not exists
      if( !isset( $preMatrix['middle'] ) )
        $preMatrix['middle'] = array();

      foreach( $balancedStacks as $colPos => $stack )
      {

        // add vals if not exists
        if( !isset( $preMatrix['middle'][$colPos] ) )
          $preMatrix['middle'][$colPos] = array();

        $elements = $stack->elements;

        foreach( $elements as $attribute )
        {

          $uiPos = $attribute->uiElement->position();
          $fName = $attribute->fullName();

          // add vals if not exists
          if( !isset( $preMatrix['middle'][$colPos][(int)$uiPos->priority] ) )
            $preMatrix['middle'][$colPos][(int)$uiPos->priority] = array();

          $preMatrix['middle'][$colPos][(int)$uiPos->priority][$fName] =  $attribute;

        }

      }

    }//end else

    $matrix = $this->createFinalMatrix( $preMatrix );
    return $matrix;


  }//end public function getCategoryAttributes */

  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getMainManagement()
  {
    return $this->management;
  }//end public function getMainManagement */

  /**
   * @param $preMatrix
   */
  protected function createFinalMatrix( $preMatrix )
  {

    $matrix = array();


    if( isset( $preMatrix['hidden'] ) )
    {
      $hiddenMatrix = array();
      $hiddenStack  = $this->sortSimpleStack( $preMatrix['hidden'] );

      foreach( $hiddenStack as $entry )
      {
        //$hiddenMatrix[] = $entry->unpack();
        $hiddenMatrix[] = $entry;

        // extracting recursiv all children
        $hiddenMatrix   = $this->extractAttrChildren( $hiddenMatrix ,  $entry );

      }

      $matrix['hidden'] = $hiddenMatrix;

    }//end hidden

    if( isset( $preMatrix['top'] ) )
    {
      $topMatrix = array();
      $topStack = $this->sortSimpleStack( $preMatrix['top'] );

      foreach( $topStack as $prioList )
      {

        krsort( $prioList, SORT_NUMERIC  );

        foreach( $prioList as $entry )
        {
          //$topMatrix[]  = $entry->unpack();
          $topMatrix[]  = $entry;

          // extracting recursiv all children
          $topMatrix    = $this->extractAttrChildren( $topMatrix ,  $entry );
        }

      }

      $matrix['top'] = $topMatrix;

    }//end top


    // check the main area
    if( isset( $preMatrix['middle'] ) )
    {
      $middleMatrix     = array();
      $preMiddleMatrix  = $preMatrix['middle'];

      foreach( $preMiddleMatrix as $stackName => $preMiddleStack )
      {
        $middleStack  = $this->sortSimpleStack($preMiddleStack);
        $tmpList      = array();

        foreach( $middleStack as $prioList  )
        {
          krsort( $prioList, SORT_NUMERIC  );

          foreach( $prioList as $entry )
          {
            //$tmpList[]  = $entry->unpack();
            $tmpList[]  = $entry;

            // extracting recursiv all children
            $tmpList    = $this->extractAttrChildren( $tmpList, $entry );
          }
        }

        $middleMatrix[$stackName] = $tmpList;

      }//end foreach( $preMiddleMatrix as $stackName => $preMiddleStack )


      // dirty fix to remove double entries
      $tmpMatrix = array();
      $blackList = array();

      foreach( $middleMatrix as $stack => $stackContent )
      {
        $stackMatrix = array();

        foreach( $stackContent as $attribute )
        {
          $fName = $attribute->fullName();

          if( isset( $blackList[$fName]) )
            continue;
          else
            $blackList[$fName] = true;

          $stackMatrix[] = $attribute;
        }

        $tmpMatrix[] = $stackMatrix;

      }

      $matrix['middle'] = $tmpMatrix;

    }//end middle

    // check the bottom area
    if(isset($preMatrix['bottom']))
    {
      $bottomMatrix = array();
      $bottomStack  = $this->sortSimpleStack($preMatrix['bottom']);

      foreach( $bottomStack as $prioList )
      {

        krsort( $prioList, SORT_NUMERIC  );
        foreach( $prioList as $entry )
        {

          //$bottomMatrix[] = $entry->unpack();
          $bottomMatrix[] = $entry;

          // extracting recursiv all children
          $bottomMatrix   = $this->extractAttrChildren( $bottomMatrix, $entry );
        }

      }

      $matrix['bottom'] = $bottomMatrix;

    }//end bottom

    return $matrix;

  }//end protected function createFinalMatrix */

  /**
   * @param TArray $list
   * @param TArray $entry
   * @return array
   */
  protected function extractAttrChildren( $list ,  $entry )
  {

    if( $children = $entry->getChildren() )
    {

      foreach( $children as $child )
      {
         //$list[]  = $child->unpack();
         $list[]  = $child;
         $list    = $this->extractAttrChildren( $list ,  $child );
      }

    }

    return $list;

  }//end protected function extractAttrChildren */

  /**
   * fill up the stacks by adding always one
   * @param TArray $autoLayout
   * @param TArray $autoAppend
   */
  protected function fillUpStacks( $autoLayout , $autoAppend )
  {

    $stack = $this->calcAllocaterStack( $autoLayout );

    $attrLists = $autoAppend->asArray();

    foreach( $attrLists as $attribute )
    {

      // this are the moments where i hate php
      $elements   = $stack->pointerSmallest->elements;
      $elements[] = $attribute;
      $stack->pointerSmallest->elements = $elements;

      $stack->smallest  += $attribute->weight();

      if( $stack->smallest > $stack->next )
      {
        $stack = $this->calcAllocaterStack( $autoLayout );
      }

    }//end foreach( $autoAppend as $attribute )

    return $autoLayout;

  }//end protected function fillUpStacks */

  /**
   * calculate an allocater stack
   * means a data structure with the smallest, second smallest and the biggest stack
   * @param TArray $autoLayout
   * @return TArray
   */
  protected function calcAllocaterStack( $autoLayout )
  {

    $stack                  = new TArray();
    $stack->smallest        = 100000;
    $stack->next            = null;
    //$stack->biggest         = 0;
    $stack->pointerSmallest = null;

    $autoLayout->rewind();

    foreach( $autoLayout as $layoutCol )
    {

      $weight = $layoutCol->weight();

      if( is_null( $stack->next ) )
        $stack->next = $weight;

      // smaller than smallest must be new smallest
      if( $weight < $stack->smallest  )
      {
        $stack->smallest = $weight;
      }

      // smaller the next but bigger than smallest is new next
      if( $weight < $stack->next && $weight > $stack->smallest  )
      {
        $stack->next = $weight;
      }

    }//end foreach( $autoLayout as $layout )

    $autoLayout->rewind();

    // add the smallest layouts
    foreach( $autoLayout as $layout )
    {
      if( $layout->weight() == $stack->smallest )
      {
        // use the first smallest
        $stack->pointerSmallest = $layout;
        break;
      }
    }

    return $stack;

  }//end public function calcAllocaterStack */

  /**
   * sort the entries in a stack by priority and Naming
   * @param TArray $stack
   * @return TArray
   */
  protected function sortAutoStack( $stack )
  {
    /// TODO implement later when this works
    return $stack;
  }//end protected function sortAutoStack */

  /**
   * sort the entries in a stack by priority and Naming
   * @param TArray $stack
   * @return TArray
   */
  protected function sortSimpleStack( $stack )
  {
    /// TODO implement later when this works
    return $stack;
  }//end protected function sortSimpleStack */


/*//////////////////////////////////////////////////////////////////////////////
// experimental code
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @param LibGenfTreeNodeCategory $category
   * @param int $numCols
   * @param boolean $forceColSize
   * @return TArray
   */
  public function getCategoryLayout( $category, $numCols = 2, $forceColSize = false )
  {

    $layouts          = $category->contextLayouts( $numCols, $forceColSize );

    // all layouts that should get append automatically new elements
    $autoLayout       = new TArray();

    foreach( $layouts as $pos => $layout )
    {
      if( 'auto' == $layout->fill )
      {
        $autoLayout[$pos] = $layout;
      }
      else 
      {
        $autoLayout[$pos] = false;
      }
    }

    return $autoLayout;

  }//end public function getCategoryLayout */


  /**
   *
   * @param string $category
   * @return LibGenfTreeNodeCategoryManagement
   */
  public function getCategory( $catName )
  {

    $category         = $this->categories[$catName];
    $category->defNum = $this->defColNumbers;

    return $category;

  }//end public function getCategory */


  /**
   * @param LibGenfEnvManagement
   * @param string $catName
   * @return array
   */
  public function createFormBlock( $env, $catName , $numCols = null, $force = false )
  {

    if( is_null($numCols) )
      $numCols  = $this->defColNumbers;

    $category   = $env->getCategory( $catName );
    $fields     = $env->getCategoryFields( $catName );

    $autoLayout = $category->contextLayouts( $numCols, $force );

    $autoAppend = new TArray();
    $this->buildPreMatrix( $fields , $autoAppend );


    // if only one, don't thin just do
    if( 1 === count($autoLayout) )
    {

       // only one col to balance, just append the rest
       $preMatrix = $this->autoBalanceSingle( $preMatrix , $autoAppend );

    }
    else
    {

      // ok now we have a little problem
      // we have x stacks, with pontential different stacksizes of ui elements
      // an we have a number y of auto appended ui elements
      // now we have to fill upp all stacks, goal ist to fill up them as
      // consistent as possible
      // strategy is to fill up the smallest first until all stacks are equal
      // after that the rest of the entries is added equally until the auto stack
      // is empty
      $balancedStacks = $this->fillUpStacks( $autoLayout , $autoAppend );

      // autobalance the rest of the fields
      $preMatrix      = $this->autoBalanceMulti( $preMatrix , $balancedStacks );

    }//end else

    return $this->assembleFormFields($preMatrix);

  }//end public function getCategoryAttributes */

  /**
   *
   * @param array $fields
   * @param TArray $autoAppend
   */
  public function buildPreMatrix( $fields , $autoAppend )
  {

    $preMatrix          = array();
    $appended           = array();
    $hiddenAttributes   = array();

    $fieldIndex  = $fields;

    // assign all attributes
    foreach( $fields as $attribute )
    {

      $uiPos    = $attribute->uiElement->position();
      $fName    = $attribute->fullName();
      $mgmtName = $attribute->management->name;

      // some dirty reduncance prevention for search
      if( isset( $appended[$fName] ) )
        continue;
      else
        $appended[$fName] = true;


      if( $attribute->hidden( ) )
      {
        $hiddenAttributes[] = $attribute;
        continue;
      }


      $targetKey = null;

      if( $uiPos->relation )
        $targetKey = $mgmtName.'-'.$uiPos->target;

      $al   = $uiPos->align;
      $val  = $uiPos->valign;
      $prio = (int)$uiPos->priority;

      // if parent attribut exists, append, else add normal in the matrix
      if( $uiPos->relation && isset( $fieldIndex[$targetKey] )  )
      {

        if( isset( $fieldIndex[$targetKey] ) )
        {
          $fieldIndex[$targetKey]->addChild($attribute);
        }

      }//end if( $uiPos->relation )
      else
      {

        // create subfields if not yet exist
        if( !isset($preMatrix[$val]) )
          $preMatrix[$val] = array();

        // single cols have only one col so the align is not relevant
        if( in_array( $val , $this->singleCol ) )
        {
          // create subfields if not yet exist
          if( !isset($preMatrix[$val][$prio]) )
            $preMatrix[$val][$prio] = array();

          $preMatrix[$val][$prio][$fName]  =  $attribute;
        }
        else
        {

          // if auto or the col not exists autoappend the attribute later
          if( 'auto' == $al )
          {
            $autoAppend->append($attribute);
          }
          elseif( !isset($auto[$al]) )
          {
            $autoAppend->append($attribute);
          }
          // if this attribute maps to a auto asign
          else if( isset($autoLayout[$al]) )
          {

            $elements   = $autoLayout[$al]->elements;
            $elements[] = $attribute;
            $autoLayout[$al]->elements = $elements;

            $autoLayout[$al]->weight += $attribute->weight();
            $attribute->layoutCol = $autoLayout[$al];

          }
          else // only cols that are not filled up automatically
          {
            // create subfields if not yet exist
            if( !isset($preMatrix[$val][$al]) )
              $preMatrix[$val][$al] = array();

            if( !isset($preMatrix[$val][$al][$prio]) )
              $preMatrix[$val][$al][$prio] = array();

            $preMatrix[$val][$al][$prio][$fName] =  $attribute;

          }

        }//end else

      }//end else


    }//end foreach( $fields as $attribute )

    // append the hidden attributes
    if( $hiddenAttributes )
      $preMatrix['hidden'] = $hiddenAttributes;

    return $preMatrix;

  }//end public function buildPreMatrix */

  /**
   *
   */
  public function autoBalanceSingle( $preMatrix , $autoAppend )
  {

    // get the correct key pos, could be that there is more than one row
    // but the others are marked as not autofillup able
    //$colPos = key($autoLayout);

    // ok asume this is 0, for advanced layouts use own the <layout tag
    $colPos = 0;

    // create subfields if not yet exist
    if(!isset($preMatrix['middle']))
      $preMatrix['middle'] = array();

    if(!isset($preMatrix['middle'][$colPos]))
      $preMatrix['middle'][$colPos] = array();

    foreach( $autoAppend as $attribute )
    {
      $fName = $attribute->fullName();

      $uiPos = $attribute->uiElement->position();
      $prio = (int)$uiPos->priority;

      if(!isset($preMatrix['middle'][$colPos][$prio]))
        $preMatrix['middle'][$colPos][$prio] = array();

      $preMatrix['middle'][$colPos][$prio][$fName] = $attribute;
    }

    return $preMatrix;

  }//end public function autoBalanceSingle */


  /**
   *
   */
  public function autoBalanceMulti( $preMatrix , $balancedStacks )
  {

      // add vals if not exists
    if(!isset($preMatrix['middle']))
      $preMatrix['middle'] = array();

    foreach( $balancedStacks as $colPos => $stack )
    {

      // add vals if not exists
      if(!isset($preMatrix['middle'][$colPos]))
        $preMatrix['middle'][$colPos] = array();

      $elements = $stack->elements;

      foreach( $elements as $attribute )
      {

        $fName = $attribute->fullName();

        $uiPos = $attribute->uiElement->position();
        $prio = (int)$uiPos->priority;

        // add vals if not exists
        if(!isset($preMatrix['middle'][$colPos][$prio]))
          $preMatrix['middle'][$colPos][$prio] = array();

        $preMatrix['middle'][$colPos][$prio][$fName] =  $attribute;

      }

    }

    return $preMatrix;

  }//end public function autoBalanceSingle */


/**
   * @param $preMatrix
   * @return array
   */
  protected function assembleFormFields( $preMatrix )
  {

    $matrix = array();


    if(isset($preMatrix['hidden']))
    {
      $hiddenMatrix = array();
      $hiddenStack  = $this->sortSimpleStack($preMatrix['hidden']);

      foreach( $hiddenStack as $entry )
      {
        //$hiddenMatrix[] = $entry->unpack();
        $hiddenMatrix[] = $entry;

        // extracting recursiv all children
        $hiddenMatrix   = $this->extractAttrChildren( $hiddenMatrix ,  $entry );

      }

      $matrix['hidden'] = $hiddenMatrix;

    }//end hidden

    if( isset($preMatrix['top']) )
    {
      $topMatrix = array();
      $topStack = $this->sortSimpleStack($preMatrix['top']);

      foreach( $topStack as $prioList )
      {

        krsort( $prioList, SORT_NUMERIC  );

        foreach( $prioList as $entry )
        {
          //$topMatrix[]  = $entry->unpack();
          $topMatrix[]  = $entry;

          // extracting recursiv all children
          $topMatrix    = $this->extractAttrChildren( $topMatrix ,  $entry );
        }

      }

      $matrix['top'] = $topMatrix;

    }//end top


    // check the main area
    if(isset($preMatrix['middle']))
    {
      $middleMatrix     = array();
      $preMiddleMatrix  = $preMatrix['middle'];

      foreach( $preMiddleMatrix as $stackName => $preMiddleStack )
      {
        $middleStack  = $this->sortSimpleStack($preMiddleStack);
        $tmpList      = array();

        foreach( $middleStack as $prioList  )
        {
          krsort( $prioList, SORT_NUMERIC  );

          foreach( $prioList as $entry )
          {
            //$tmpList[]  = $entry->unpack();
            $tmpList[]  = $entry;

            // extracting recursiv all children
            $tmpList    = $this->extractAttrChildren( $tmpList, $entry );
          }
        }

        $middleMatrix[$stackName] = $tmpList;

      }//end foreach( $preMiddleMatrix as $stackName => $preMiddleStack )


      // dirty fix to remove double entries
      $tmpMatrix = array();
      $blackList = array();

      foreach( $middleMatrix as $stack => $stackContent )
      {
        $stackMatrix = array();

        foreach( $stackContent as $attribute )
        {
          $fName = $attribute->fullName();

          if( isset($blackList[$fName]) )
            continue;
          else
            $blackList[$fName] = true;

          $stackMatrix[] = $attribute;
        }

        $tmpMatrix[] = $stackMatrix;

      }

      $matrix['middle'] = $tmpMatrix;

    }//end middle

    // check the bottom area
    if(isset($preMatrix['bottom']))
    {
      $bottomMatrix = array();
      $bottomStack  = $this->sortSimpleStack($preMatrix['bottom']);

      foreach( $bottomStack as $prioList )
      {

        krsort( $prioList, SORT_NUMERIC  );
        foreach( $prioList as $entry )
        {

          //$bottomMatrix[] = $entry->unpack();
          $bottomMatrix[] = $entry;

          // extracting recursiv all children
          $bottomMatrix   = $this->extractAttrChildren( $bottomMatrix, $entry );
        }

      }

      $matrix['bottom'] = $bottomMatrix;

    }//end bottom

    return $matrix;


  }//end protected function assembleFormFields */



}//end class LibGenfFormbuilder

